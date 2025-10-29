const express = require('express');
const router = express.Router();
const { v4: uuidv4 } = require('uuid');
const db = require('../config/database');

// GET all sectors
router.get('/', async (req, res) => {
  try {
    const [rows] = await db.query('SELECT * FROM sectors ORDER BY name');
    res.json(rows);
  } catch (error) {
    console.error('Error fetching sectors:', error);
    res.status(500).json({ error: 'Erro ao buscar setores' });
  }
});

// GET sector by ID
router.get('/:id', async (req, res) => {
  try {
    const [rows] = await db.query('SELECT * FROM sectors WHERE id = ?', [req.params.id]);
    if (rows.length === 0) {
      return res.status(404).json({ error: 'Setor não encontrado' });
    }
    res.json(rows[0]);
  } catch (error) {
    console.error('Error fetching sector:', error);
    res.status(500).json({ error: 'Erro ao buscar setor' });
  }
});

// POST create sector
router.post('/', async (req, res) => {
  try {
    const { name, description } = req.body;

    if (!name) {
      return res.status(400).json({ error: 'Nome do setor é obrigatório' });
    }

    const id = uuidv4();
    await db.query(
      'INSERT INTO sectors (id, name, description) VALUES (?, ?, ?)',
      [id, name, description || null]
    );

    const [rows] = await db.query('SELECT * FROM sectors WHERE id = ?', [id]);
    res.status(201).json(rows[0]);
  } catch (error) {
    console.error('Error creating sector:', error);
    res.status(500).json({ error: 'Erro ao criar setor' });
  }
});

// PUT update sector
router.put('/:id', async (req, res) => {
  try {
    const { name, description } = req.body;

    if (!name) {
      return res.status(400).json({ error: 'Nome do setor é obrigatório' });
    }

    const [result] = await db.query(
      'UPDATE sectors SET name = ?, description = ? WHERE id = ?',
      [name, description || null, req.params.id]
    );

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Setor não encontrado' });
    }

    const [rows] = await db.query('SELECT * FROM sectors WHERE id = ?', [req.params.id]);
    res.json(rows[0]);
  } catch (error) {
    console.error('Error updating sector:', error);
    res.status(500).json({ error: 'Erro ao atualizar setor' });
  }
});

// DELETE sector
router.delete('/:id', async (req, res) => {
  try {
    // Check if sector has users or assets
    const [users] = await db.query('SELECT COUNT(*) as count FROM users WHERE sector_id = ?', [req.params.id]);
    const [assets] = await db.query('SELECT COUNT(*) as count FROM assets WHERE sector_id = ?', [req.params.id]);

    if (users[0].count > 0 || assets[0].count > 0) {
      return res.status(400).json({
        error: 'Não é possível excluir setor com usuários ou ativos associados'
      });
    }

    const [result] = await db.query('DELETE FROM sectors WHERE id = ?', [req.params.id]);

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Setor não encontrado' });
    }

    res.json({ message: 'Setor excluído com sucesso' });
  } catch (error) {
    console.error('Error deleting sector:', error);
    res.status(500).json({ error: 'Erro ao excluir setor' });
  }
});

module.exports = router;
