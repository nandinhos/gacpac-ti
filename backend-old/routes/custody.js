const express = require('express');
const router = express.Router();
const { v4: uuidv4 } = require('uuid');
const db = require('../config/database');

// GET all custody logs
router.get('/', async (req, res) => {
  try {
    const { active, userId } = req.query;
    let query = `
      SELECT cl.*,
             u.name as user_name,
             u.rank as user_rank,
             u.military_id
      FROM custody_logs cl
      INNER JOIN users u ON cl.user_id = u.id
      WHERE 1=1
    `;
    const params = [];

    if (active === 'true') {
      query += ' AND cl.checkin_date IS NULL';
    } else if (active === 'false') {
      query += ' AND cl.checkin_date IS NOT NULL';
    }

    if (userId) {
      query += ' AND cl.user_id = ?';
      params.push(userId);
    }

    query += ' ORDER BY cl.checkout_date DESC, cl.cautela_number DESC';

    const [custodyLogs] = await db.query(query, params);

    // Fetch assets for each custody log
    for (let log of custodyLogs) {
      const [assets] = await db.query(
        `SELECT a.*, ca.id as custody_asset_id
         FROM custody_assets ca
         INNER JOIN assets a ON ca.asset_id = a.id
         WHERE ca.custody_log_id = ?`,
        [log.id]
      );
      log.assetIds = assets.map(a => a.id);
      log.assets = assets;
    }

    res.json(custodyLogs);
  } catch (error) {
    console.error('Error fetching custody logs:', error);
    res.status(500).json({ error: 'Erro ao buscar cautelas' });
  }
});

// GET next cautela number for current year
router.get('/next-number', async (req, res) => {
  try {
    const currentYear = new Date().getFullYear();
    const [lastCautela] = await db.query(
      'SELECT cautela_number FROM custody_logs WHERE cautela_number LIKE ? ORDER BY cautela_number DESC LIMIT 1',
      [`%/${currentYear}`]
    );
    let nextNumber = 1;
    if (lastCautela.length > 0) {
      const lastNumberStr = lastCautela[0].cautela_number.split('/')[0];
      const lastNumber = parseInt(lastNumberStr);
      nextNumber = lastNumber + 1;
    }
    const cautela_number = `${nextNumber.toString().padStart(3, '0')}/GAC-PAC/${currentYear}`;
    res.json({ nextCautelaNumber: cautela_number });
  } catch (error) {
    console.error('Error fetching next cautela number:', error);
    res.status(500).json({ error: 'Erro ao buscar próximo número da cautela' });
  }
});

// GET custody log by ID
router.get('/:id', async (req, res) => {
  try {
    const [custodyLogs] = await db.query(
      `SELECT cl.*,
              u.name as user_name,
              u.rank as user_rank,
              u.military_id
       FROM custody_logs cl
       INNER JOIN users u ON cl.user_id = u.id
       WHERE cl.id = ?`,
      [req.params.id]
    );

    if (custodyLogs.length === 0) {
      return res.status(404).json({ error: 'Cautela não encontrada' });
    }

    const log = custodyLogs[0];

    const [assets] = await db.query(
      `SELECT a.*, ca.id as custody_asset_id
       FROM custody_assets ca
       INNER JOIN assets a ON ca.asset_id = a.id
       WHERE ca.custody_log_id = ?`,
      [log.id]
    );
    log.assetIds = assets.map(a => a.id);
    log.assets = assets;

    res.json(log);
  } catch (error) {
    console.error('Error fetching custody log:', error);
    res.status(500).json({ error: 'Erro ao buscar cautela' });
  }
});

// POST create custody log (checkout)
router.post('/', async (req, res) => {
  const connection = await db.getConnection();
  try {
    await connection.beginTransaction();

    const { user_id, checkout_date, assetIds, term_url, notes } = req.body;

    if (!user_id || !checkout_date || !assetIds || assetIds.length === 0) {
      await connection.rollback();
      return res.status(400).json({
        error: 'Usuário, data e ativos são obrigatórios'
      });
    }

    // Generate next cautela number for current year
    const currentYear = new Date().getFullYear();
    const [lastCautela] = await connection.query(
      'SELECT cautela_number FROM custody_logs WHERE cautela_number LIKE ? ORDER BY cautela_number DESC LIMIT 1',
      [`%/${currentYear}`]
    );
    let nextNumber = 1;
    if (lastCautela.length > 0) {
      const lastNumberStr = lastCautela[0].cautela_number.split('/')[0];
      const lastNumber = parseInt(lastNumberStr);
      nextNumber = lastNumber + 1;
    }
    const cautela_number = `${nextNumber.toString().padStart(3, '0')}/GAC-PAC/${currentYear}`;

    // Verify all assets are available
    const [assets] = await connection.query(
      'SELECT id, name, status FROM assets WHERE id IN (?)',
      [assetIds]
    );

    const unavailableAssets = assets.filter(a => a.status !== 'Disponível');
    if (unavailableAssets.length > 0) {
      await connection.rollback();
      return res.status(400).json({
        error: `Ativos não disponíveis: ${unavailableAssets.map(a => a.name).join(', ')}`
      });
    }

    // Create custody log
    const custodyId = uuidv4();
    await connection.query(
      `INSERT INTO custody_logs (id, cautela_number, user_id, checkout_date, term_url, notes)
       VALUES (?, ?, ?, ?, ?, ?)`,
      [custodyId, cautela_number, user_id, checkout_date, term_url || null, notes || null]
    );

    // Add assets to custody
    for (let assetId of assetIds) {
      const custodyAssetId = uuidv4();
      await connection.query(
        'INSERT INTO custody_assets (id, custody_log_id, asset_id) VALUES (?, ?, ?)',
        [custodyAssetId, custodyId, assetId]
      );

      // Update asset status and custodian
      await connection.query(
        'UPDATE assets SET status = ?, custodian_user_id = ? WHERE id = ?',
        ['Em Uso', user_id, assetId]
      );
    }

    await connection.commit();

    // Fetch created custody log
    const [rows] = await db.query(
      `SELECT cl.*,
              u.name as user_name,
              u.rank as user_rank,
              u.military_id
       FROM custody_logs cl
       INNER JOIN users u ON cl.user_id = u.id
       WHERE cl.id = ?`,
      [custodyId]
    );

    const log = rows[0];
    const [logAssets] = await db.query(
      `SELECT a.* FROM custody_assets ca
       INNER JOIN assets a ON ca.asset_id = a.id
       WHERE ca.custody_log_id = ?`,
      [custodyId]
    );
    log.assetIds = assetIds;
    log.assets = logAssets;

    res.status(201).json(log);
  } catch (error) {
    await connection.rollback();
    console.error('Error creating custody log:', error);
    res.status(500).json({ error: 'Erro ao criar cautela' });
  } finally {
    connection.release();
  }
});

// PUT checkin custody log
router.put('/:id/checkin', async (req, res) => {
  const connection = await db.getConnection();
  try {
    await connection.beginTransaction();

    const { cautela_number, user_id, checkout_date, assetIds, term_url, notes } = req.body;

    if (!user_id || !checkout_date || !assetIds || assetIds.length === 0) {
      await connection.rollback();
      return res.status(400).json({
        error: 'Usuário, data e ativos são obrigatórios'
      });
    }

    // Get custody log and assets
    const [custodyLogs] = await connection.query(
      'SELECT * FROM custody_logs WHERE id = ?',
      [req.params.id]
    );

    if (custodyLogs.length === 0) {
      await connection.rollback();
      return res.status(404).json({ error: 'Cautela não encontrada' });
    }

    const log = custodyLogs[0];

    if (log.checkin_date) {
      await connection.rollback();
      return res.status(400).json({ error: 'Cautela já foi devolvida' });
    }

    // Update custody log
    await connection.query(
      'UPDATE custody_logs SET checkin_date = ?, signed_term_url = ? WHERE id = ?',
      [checkin_date, signed_term_url || log.signed_term_url, req.params.id]
    );

    // Get assets and update status
    const [assets] = await connection.query(
      `SELECT asset_id FROM custody_assets WHERE custody_log_id = ?`,
      [req.params.id]
    );

    for (let asset of assets) {
      await connection.query(
        'UPDATE assets SET status = ?, custodian_user_id = NULL WHERE id = ?',
        ['Disponível', asset.asset_id]
      );
    }

    await connection.commit();

    // Fetch updated custody log
    const [rows] = await db.query(
      `SELECT cl.*,
              u.name as user_name,
              u.rank as user_rank
       FROM custody_logs cl
       INNER JOIN users u ON cl.user_id = u.id
       WHERE cl.id = ?`,
      [req.params.id]
    );

    res.json(rows[0]);
  } catch (error) {
    await connection.rollback();
    console.error('Error checking in custody:', error);
    res.status(500).json({ error: 'Erro ao devolver cautela' });
  } finally {
    connection.release();
  }
});

// PUT update custody log
router.put('/:id', async (req, res) => {
  try {
    const { notes, signed_term_url, term_url } = req.body;

    const [result] = await db.query(
      `UPDATE custody_logs
       SET notes = ?, signed_term_url = ?, term_url = ?
       WHERE id = ?`,
      [notes || null, signed_term_url || null, term_url || null, req.params.id]
    );

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Cautela não encontrada' });
    }

    const [rows] = await db.query(
      `SELECT cl.*,
              u.name as user_name,
              u.rank as user_rank
       FROM custody_logs cl
       INNER JOIN users u ON cl.user_id = u.id
       WHERE cl.id = ?`,
      [req.params.id]
    );

    res.json(rows[0]);
  } catch (error) {
    console.error('Error updating custody log:', error);
    res.status(500).json({ error: 'Erro ao atualizar cautela' });
  }
});

// DELETE custody log
router.delete('/:id', async (req, res) => {
  const connection = await db.getConnection();
  try {
    await connection.beginTransaction();

    // Check if custody is active
    const [custodyLogs] = await connection.query(
      'SELECT checkin_date FROM custody_logs WHERE id = ?',
      [req.params.id]
    );

    if (custodyLogs.length === 0) {
      await connection.rollback();
      return res.status(404).json({ error: 'Cautela não encontrada' });
    }

    if (!custodyLogs[0].checkin_date) {
      await connection.rollback();
      return res.status(400).json({
        error: 'Não é possível excluir cautela ativa. Realize a devolução primeiro.'
      });
    }

    // Delete custody log (will cascade to custody_assets)
    await connection.query('DELETE FROM custody_logs WHERE id = ?', [req.params.id]);

    await connection.commit();
    res.json({ message: 'Cautela excluída com sucesso' });
  } catch (error) {
    await connection.rollback();
    console.error('Error deleting custody log:', error);
    res.status(500).json({ error: 'Erro ao excluir cautela' });
  } finally {
    connection.release();
  }
});

module.exports = router;
