const express = require('express');
const router = express.Router();
const { v4: uuidv4 } = require('uuid');
const db = require('../config/database');

// GET all inventory records
router.get('/', async (req, res) => {
  try {
    const { status, sectorId } = req.query;
    let query = `
      SELECT ir.*,
             s.name as sector_name
      FROM inventory_records ir
      LEFT JOIN sectors s ON ir.sector_id = s.id
      WHERE 1=1
    `;
    const params = [];

    if (status) {
      query += ' AND ir.status = ?';
      params.push(status);
    }

    if (sectorId) {
      query += ' AND ir.sector_id = ?';
      params.push(sectorId);
    }

    query += ' ORDER BY ir.start_date DESC';

    const [inventories] = await db.query(query, params).catch(() => [[]]);

    // Fetch related data for each inventory
    for (let inventory of inventories) {
      const [found] = await db.query(
        `SELECT ifi.*, a.* FROM inventory_found_items ifi
         INNER JOIN assets a ON ifi.asset_id = a.id
         WHERE ifi.inventory_id = ?`,
        [inventory.id]
      ).catch(() => [[]]);

      const [pending] = await db.query(
        `SELECT ipi.*, a.* FROM inventory_pending_items ipi
         INNER JOIN assets a ON ipi.asset_id = a.id
         WHERE ipi.inventory_id = ?`,
        [inventory.id]
      ).catch(() => [[]]);

      const [uncatalogued] = await db.query(
        'SELECT * FROM inventory_uncatalogued_items WHERE inventory_id = ?',
        [inventory.id]
      ).catch(() => [[]]);

      const [reopenHistory] = await db.query(
        `SELECT irh.*, u.name as user_name, u.rank as user_rank
         FROM inventory_reopen_history irh
         INNER JOIN users u ON irh.reopened_by_user_id = u.id
         WHERE irh.inventory_id = ?
         ORDER BY irh.reopened_at DESC`,
        [inventory.id]
      ).catch(() => [[]]);

      inventory.foundItems = found.map(f => ({
        ...f,
        observation: f.observation
      }));
      inventory.pendingItems = pending;
      inventory.uncataloguedItems = uncatalogued;
      inventory.reopenHistory = reopenHistory;
    }

    console.log('Inventories:', inventories);
    res.json(inventories);
  } catch (error) {
    console.error('Error fetching inventories:', error);
    // Return empty array if table doesn't exist
    res.json([]);
  }
});

// GET inventory by ID
router.get('/:id', async (req, res) => {
  try {
    const [inventories] = await db.query(
      `SELECT ir.*,
              s.name as sector_name
       FROM inventory_records ir
       LEFT JOIN sectors s ON ir.sector_id = s.id
       WHERE ir.id = ?`,
      [req.params.id]
    );

    if (inventories.length === 0) {
      return res.status(404).json({ error: 'Inventário não encontrado' });
    }

    const inventory = inventories[0];

    const [found] = await db.query(
      `SELECT ifi.*, a.* FROM inventory_found_items ifi
       INNER JOIN assets a ON ifi.asset_id = a.id
       WHERE ifi.inventory_id = ?`,
      [inventory.id]
    );

    const [pending] = await db.query(
      `SELECT ipi.*, a.* FROM inventory_pending_items ipi
       INNER JOIN assets a ON ipi.asset_id = a.id
       WHERE ipi.inventory_id = ?`,
      [inventory.id]
    );

    const [uncatalogued] = await db.query(
      'SELECT * FROM inventory_uncatalogued_items WHERE inventory_id = ?',
      [inventory.id]
    );

    const [reopenHistory] = await db.query(
      `SELECT irh.*, u.name as user_name, u.rank as user_rank
       FROM inventory_reopen_history irh
       INNER JOIN users u ON irh.reopened_by_user_id = u.id
       WHERE irh.inventory_id = ?
       ORDER BY irh.reopened_at DESC`,
      [inventory.id]
    );

    inventory.foundItems = found.map(f => ({
      ...f,
      observation: f.observation
    }));
    inventory.pendingItems = pending;
    inventory.uncataloguedItems = uncatalogued;
    inventory.reopenHistory = reopenHistory;

    res.json(inventory);
  } catch (error) {
    console.error('Error fetching inventory:', error);
    res.status(500).json({ error: 'Erro ao buscar inventário' });
  }
});

// POST create inventory
router.post('/', async (req, res) => {
  const connection = await db.getConnection();
  try {
    await connection.beginTransaction();

    const { commissionNumber, startDate, sectorId, responsibleUserId, notes } = req.body;

    if (!commissionNumber || !startDate) {
      await connection.rollback();
      return res.status(400).json({
        error: 'Número da comissão e data de início são obrigatórios'
      });
    }

    // Check if commission number already exists
    const [existing] = await connection.query(
      'SELECT id FROM inventory_records WHERE commission_number = ?',
      [commissionNumber]
    );
    if (existing.length > 0) {
      await connection.rollback();
      return res.status(400).json({ error: 'Número de comissão já existe' });
    }

    const id = uuidv4();
    await connection.query(
      `INSERT INTO inventory_records (id, commission_number, start_date, sector_id, status, notes)
       VALUES (?, ?, ?, ?, ?, ?)`,
      [id, commissionNumber, startDate, sectorId || null, 'Em Andamento', notes || null]
    ).catch((err) => {
      if (err.code === 'ER_NO_SUCH_TABLE') {
        throw new Error('Tabela de inventários não existe. Execute o init.sql.');
      }
      throw err;
    });

    // Get all assets for pending items (filtered by sector if specified)
    let assetsQuery = 'SELECT id FROM assets WHERE 1=1';
    const assetsParams = [];

    if (sectorId) {
      assetsQuery += ' AND sector_id = ?';
      assetsParams.push(sectorId);
    }

    const [assets] = await connection.query(assetsQuery, assetsParams);

    // Add all assets to pending items
    for (let asset of assets) {
      const pendingId = uuidv4();
      await connection.query(
        'INSERT INTO inventory_pending_items (id, inventory_id, asset_id) VALUES (?, ?, ?)',
        [pendingId, id, asset.id]
      );
    }

    await connection.commit();

    const [rows] = await db.query(
      `SELECT ir.*, s.name as sector_name
       FROM inventory_records ir
       LEFT JOIN sectors s ON ir.sector_id = s.id
       WHERE ir.id = ?`,
      [id]
    );

    const inventory = rows[0];
    inventory.foundItems = [];
    inventory.pendingItems = assets.map(a => ({ id: a.id }));
    inventory.uncataloguedItems = [];
    inventory.reopenHistory = [];

    res.status(201).json(inventory);
  } catch (error) {
    await connection.rollback();
    console.error('Error creating inventory:', error);
    res.status(500).json({ error: 'Erro ao criar inventário' });
  } finally {
    connection.release();
  }
});

// POST add found item
router.post('/:id/found', async (req, res) => {
  const connection = await db.getConnection();
  try {
    await connection.beginTransaction();

    const { assetId, observation } = req.body;

    if (!assetId) {
      await connection.rollback();
      return res.status(400).json({ error: 'ID do ativo é obrigatório' });
    }

    // Remove from pending if exists
    await connection.query(
      'DELETE FROM inventory_pending_items WHERE inventory_id = ? AND asset_id = ?',
      [req.params.id, assetId]
    );

    // Add to found items
    const foundId = uuidv4();
    await connection.query(
      'INSERT INTO inventory_found_items (id, inventory_id, asset_id, observation) VALUES (?, ?, ?, ?)',
      [foundId, req.params.id, assetId, observation || null]
    );

    await connection.commit();

    const [rows] = await db.query(
      `SELECT ifi.*, a.* FROM inventory_found_items ifi
       INNER JOIN assets a ON ifi.asset_id = a.id
       WHERE ifi.id = ?`,
      [foundId]
    );

    res.status(201).json(rows[0]);
  } catch (error) {
    await connection.rollback();
    console.error('Error adding found item:', error);
    res.status(500).json({ error: 'Erro ao adicionar item encontrado' });
  } finally {
    connection.release();
  }
});

// POST add uncatalogued item
router.post('/:id/uncatalogued', async (req, res) => {
  try {
    const { description, location } = req.body;

    if (!description) {
      return res.status(400).json({ error: 'Descrição é obrigatória' });
    }

    const uncataloguedId = uuidv4();
    await db.query(
      'INSERT INTO inventory_uncatalogued_items (id, inventory_id, description, location) VALUES (?, ?, ?, ?)',
      [uncataloguedId, req.params.id, description, location || null]
    );

    const [rows] = await db.query(
      'SELECT * FROM inventory_uncatalogued_items WHERE id = ?',
      [uncataloguedId]
    );

    res.status(201).json(rows[0]);
  } catch (error) {
    console.error('Error adding uncatalogued item:', error);
    res.status(500).json({ error: 'Erro ao adicionar item não catalogado' });
  }
});

// PUT complete inventory
router.put('/:id/complete', async (req, res) => {
  try {
    const { endDate } = req.body;

    if (!endDate) {
      return res.status(400).json({ error: 'Data de conclusão é obrigatória' });
    }

    const [result] = await db.query(
      'UPDATE inventory_records SET status = ?, end_date = ? WHERE id = ?',
      ['Concluído', endDate, req.params.id]
    );

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Inventário não encontrado' });
    }

    const [rows] = await db.query('SELECT * FROM inventory_records WHERE id = ?', [req.params.id]);
    res.json(rows[0]);
  } catch (error) {
    console.error('Error completing inventory:', error);
    res.status(500).json({ error: 'Erro ao concluir inventário' });
  }
});

// POST reopen inventory
router.post('/:id/reopen', async (req, res) => {
  const connection = await db.getConnection();
  try {
    await connection.beginTransaction();

    const { userId, justification } = req.body;

    if (!userId || !justification) {
      await connection.rollback();
      return res.status(400).json({
        error: 'Usuário e justificativa são obrigatórios'
      });
    }

    // Check if inventory is completed
    const [inventories] = await connection.query(
      'SELECT status FROM inventory_records WHERE id = ?',
      [req.params.id]
    );

    if (inventories.length === 0) {
      await connection.rollback();
      return res.status(404).json({ error: 'Inventário não encontrado' });
    }

    if (inventories[0].status !== 'Concluído') {
      await connection.rollback();
      return res.status(400).json({ error: 'Apenas inventários concluídos podem ser reabertos' });
    }

    // Add reopen history entry
    const reopenId = uuidv4();
    const reopenedAt = new Date();
    await connection.query(
      `INSERT INTO inventory_reopen_history (id, inventory_id, reopened_by_user_id, reopened_at, justification)
       VALUES (?, ?, ?, ?, ?)`,
      [reopenId, req.params.id, userId, reopenedAt, justification]
    );

    // Update inventory status
    await connection.query(
      'UPDATE inventory_records SET status = ?, end_date = NULL WHERE id = ?',
      ['Reaberto', req.params.id]
    );

    await connection.commit();

    const [rows] = await db.query('SELECT * FROM inventory_records WHERE id = ?', [req.params.id]);
    res.json(rows[0]);
  } catch (error) {
    await connection.rollback();
    console.error('Error reopening inventory:', error);
    res.status(500).json({ error: 'Erro ao reabrir inventário' });
  } finally {
    connection.release();
  }
});

// DELETE inventory
router.delete('/:id', async (req, res) => {
  try {
    const [result] = await db.query('DELETE FROM inventory_records WHERE id = ?', [req.params.id]);

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Inventário não encontrado' });
    }

    res.json({ message: 'Inventário excluído com sucesso' });
  } catch (error) {
    console.error('Error deleting inventory:', error);
    res.status(500).json({ error: 'Erro ao excluir inventário' });
  }
});

// DELETE uncatalogued item
router.delete('/:id/uncatalogued/:uncataloguedId', async (req, res) => {
  try {
    const [result] = await db.query(
      'DELETE FROM inventory_uncatalogued_items WHERE id = ? AND inventory_id = ?',
      [req.params.uncataloguedId, req.params.id]
    );

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Item não catalogado não encontrado' });
    }

    res.json({ message: 'Item não catalogado excluído com sucesso' });
  } catch (error) {
    console.error('Error deleting uncatalogued item:', error);
    res.status(500).json({ error: 'Erro ao excluir item' });
  }
});

module.exports = router;
