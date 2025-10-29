const express = require('express');
const router = express.Router();
const { v4: uuidv4 } = require('uuid');
const db = require('../config/database');

// GET all users
router.get('/', async (req, res) => {
  try {
    const { active, sectorId } = req.query;
    let query = `
      SELECT u.*, s.name as sector_name
      FROM users u
      LEFT JOIN sectors s ON u.sector_id = s.id
      WHERE 1=1
    `;
    const params = [];

    if (active !== undefined) {
      query += ' AND u.is_active = ?';
      params.push(active === 'true' ? 1 : 0);
    }

    if (sectorId) {
      query += ' AND u.sector_id = ?';
      params.push(sectorId);
    }

    query += ' ORDER BY u.rank, u.name';

    const [rows] = await db.query(query, params);
    // Convert is_active (0/1) to boolean for frontend compatibility
    const users = rows.map(user => ({
      ...user,
      is_active: Boolean(user.is_active)
    }));
    res.json(users);
  } catch (error) {
    console.error('Error fetching users:', error);
    res.status(500).json({ error: 'Erro ao buscar usuários' });
  }
});

// GET user by ID
router.get('/:id', async (req, res) => {
  try {
    const [rows] = await db.query(
      `SELECT u.*, s.name as sector_name
       FROM users u
       LEFT JOIN sectors s ON u.sector_id = s.id
       WHERE u.id = ?`,
      [req.params.id]
    );

    if (rows.length === 0) {
      return res.status(404).json({ error: 'Usuário não encontrado' });
    }
    const user = {
      ...rows[0],
      is_active: Boolean(rows[0].is_active)
    };
    res.json(user);
  } catch (error) {
    console.error('Error fetching user:', error);
    res.status(500).json({ error: 'Erro ao buscar usuário' });
  }
});

// POST create user
router.post('/', async (req, res) => {
  try {
    const { name, rank, militaryId, sectorId, email, phone, isActive } = req.body;

    if (!name || !rank || !militaryId) {
      return res.status(400).json({
        error: 'Nome, posto/graduação e identidade militar são obrigatórios'
      });
    }

    // Check if military ID already exists
    const [existing] = await db.query('SELECT id FROM users WHERE military_id = ?', [militaryId]);
    if (existing.length > 0) {
      return res.status(400).json({ error: 'Identidade militar já cadastrada' });
    }

    const id = uuidv4();
    await db.query(
      `INSERT INTO users (id, name, rank, military_id, sector_id, email, phone, is_active)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
      [id, name, rank, militaryId, sectorId || null, email || null, phone || null, isActive !== false ? 1 : 0]
    );

    const [rows] = await db.query(
      `SELECT u.*, s.name as sector_name
       FROM users u
       LEFT JOIN sectors s ON u.sector_id = s.id
       WHERE u.id = ?`,
      [id]
    );
    const user = {
      ...rows[0],
      is_active: Boolean(rows[0].is_active)
    };
    res.status(201).json(user);
  } catch (error) {
    console.error('Error creating user:', error);
    res.status(500).json({ error: 'Erro ao criar usuário' });
  }
});

// PUT update user
router.put('/:id', async (req, res) => {
  try {
    const { name, rank, militaryId, sectorId, email, phone, isActive } = req.body;

    if (!name || !rank || !militaryId) {
      return res.status(400).json({
        error: 'Nome, posto/graduação e identidade militar são obrigatórios'
      });
    }

    // Check if military ID is used by another user
    const [existing] = await db.query(
      'SELECT id FROM users WHERE military_id = ? AND id != ?',
      [militaryId, req.params.id]
    );
    if (existing.length > 0) {
      return res.status(400).json({ error: 'Identidade militar já cadastrada' });
    }

    const [result] = await db.query(
      `UPDATE users
       SET name = ?, rank = ?, military_id = ?, sector_id = ?,
           email = ?, phone = ?, is_active = ?
       WHERE id = ?`,
      [name, rank, militaryId, sectorId || null, email || null, phone || null,
       isActive !== false ? 1 : 0, req.params.id]
    );

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Usuário não encontrado' });
    }

    const [rows] = await db.query(
      `SELECT u.*, s.name as sector_name
       FROM users u
       LEFT JOIN sectors s ON u.sector_id = s.id
       WHERE u.id = ?`,
      [req.params.id]
    );
    const user = {
      ...rows[0],
      is_active: Boolean(rows[0].is_active)
    };
    res.json(user);
  } catch (error) {
    console.error('Error updating user:', error);
    res.status(500).json({ error: 'Erro ao atualizar usuário' });
  }
});

// DELETE user
router.delete('/:id', async (req, res) => {
  try {
    // Check if user has custody logs
    const [custody] = await db.query(
      'SELECT COUNT(*) as count FROM custody_logs WHERE user_id = ?',
      [req.params.id]
    );

    if (custody[0].count > 0) {
      return res.status(400).json({
        error: 'Não é possível excluir usuário com cautelas associadas'
      });
    }

    const [result] = await db.query('DELETE FROM users WHERE id = ?', [req.params.id]);

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Usuário não encontrado' });
    }

    res.json({ message: 'Usuário excluído com sucesso' });
  } catch (error) {
    console.error('Error deleting user:', error);
    res.status(500).json({ error: 'Erro ao excluir usuário' });
  }
});

module.exports = router;
