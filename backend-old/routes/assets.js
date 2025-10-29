const express = require('express');
const router = express.Router();
const { v4: uuidv4 } = require('uuid');
const db = require('../config/database');
const multer = require('multer');
const path = require('path');

// Helper function to process photos
const processPhotos = (photos) => {
  return photos.map(photo => {
    if (photo.photo_data) {
      let photoData;
      try {
        // Ensure photo_data is a Buffer before converting
        if (photo.photo_data instanceof Buffer) {
          photoData = photo.photo_data.toString('base64');
        } else if (typeof photo.photo_data === 'string') {
          // If it's already a string, assume it's base64
          photoData = photo.photo_data;
        } else {
          console.error('Unexpected photo_data type:', typeof photo.photo_data);
          return photo; // Return photo without URL if conversion fails
        }

        return {
          ...photo,
          url: `data:${photo.mime_type};base64,${photoData}`,
          photo_data: undefined, // Remove raw data before sending
        };
      } catch (error) {
        console.error('Error converting photo to base64:', error);
        return photo; // Return photo without URL if conversion fails
      }
    }
    return photo;
  });
};

// Configure multer for file uploads
const storage = multer.memoryStorage();

const upload = multer({
  storage: storage,
  limits: { fileSize: 5 * 1024 * 1024 }, // 5MB
  fileFilter: (req, file, cb) => {
    const filetypes = /jpeg|jpg|png|gif/;
    const mimetype = filetypes.test(file.mimetype);
    const extname = filetypes.test(path.extname(file.originalname).toLowerCase());
    if (mimetype && extname) {
      return cb(null, true);
    }
    cb(new Error('Apenas arquivos de imagem são permitidos'));
  }
});

// GET all assets
router.get('/', async (req, res) => {
  try {
    const { category, status, sectorId, search } = req.query;
    let query = `
      SELECT a.*,
             s.name as sector_name,
             u.name as custodian_name,
             u.rank as custodian_rank
      FROM assets a
      LEFT JOIN sectors s ON a.sector_id = s.id
      LEFT JOIN users u ON a.custodian_user_id = u.id
      WHERE 1=1
    `;
    const params = [];

    if (category) {
      query += ' AND a.category = ?';
      params.push(category);
    }

    if (status) {
      query += ' AND a.status = ?';
      params.push(status);
    }

    if (sectorId) {
      query += ' AND a.sector_id = ?';
      params.push(sectorId);
    }

    if (search) {
      query += ' AND (a.name LIKE ? OR a.qr_code LIKE ? OR a.serial_number LIKE ? OR a.patrimony_id LIKE ? OR a.conta LIKE ? OR a.categoria_inventario LIKE ? OR a.bmp LIKE ? OR a.componente LIKE ?)';
      const searchPattern = `%${search}%`;
      params.push(searchPattern, searchPattern, searchPattern, searchPattern, searchPattern, searchPattern, searchPattern, searchPattern);
    }

    query += ' ORDER BY a.updated_at DESC';

    const [assets] = await db.query(query, params);

    // Fetch photos and maintenance for each asset
    for (let asset of assets) {
      const [photos] = await db.query('SELECT id, asset_id, mime_type, caption, uploaded_at, photo_data FROM asset_photos WHERE asset_id = ? ORDER BY uploaded_at', [asset.id]);
      const [maintenance] = await db.query('SELECT * FROM maintenance_history WHERE asset_id = ? ORDER BY date DESC', [asset.id]);

      asset.photos = processPhotos(photos);
      asset.maintenanceHistory = maintenance;
    }

    res.json(assets);
  } catch (error) {
    console.error('Error fetching assets:', error);
    res.status(500).json({ error: 'Erro ao buscar ativos' });
  }
});

// GET asset by ID
router.get('/:id', async (req, res) => {
  try {
    const [assets] = await db.query(
      `SELECT a.*,
              s.name as sector_name,
              u.name as custodian_name,
              u.rank as custodian_rank
       FROM assets a
       LEFT JOIN sectors s ON a.sector_id = s.id
       LEFT JOIN users u ON a.custodian_user_id = u.id
       WHERE a.id = ?`,
      [req.params.id]
    );

    if (assets.length === 0) {
      return res.status(404).json({ error: 'Ativo não encontrado' });
    }

    const asset = assets[0];

    // Fetch photos and maintenance
    const [photos] = await db.query('SELECT id, asset_id, mime_type, caption, uploaded_at, photo_data FROM asset_photos WHERE asset_id = ? ORDER BY uploaded_at', [asset.id]);
    const [maintenance] = await db.query('SELECT * FROM maintenance_history WHERE asset_id = ? ORDER BY date DESC', [asset.id]);

    asset.photos = processPhotos(photos);
    asset.maintenanceHistory = maintenance;

    res.json(asset);
  } catch (error) {
    console.error('Error fetching asset:', error);
    res.status(500).json({ error: 'Erro ao buscar ativo' });
  }
});

// GET asset by QR code
router.get('/qr/:qrCode', async (req, res) => {
  try {
    const [assets] = await db.query(
      `SELECT a.*,
              s.name as sector_name,
              u.name as custodian_name,
              u.rank as custodian_rank
       FROM assets a
       LEFT JOIN sectors s ON a.sector_id = s.id
       LEFT JOIN users u ON a.custodian_user_id = u.id
       WHERE a.qr_code = ?`,
      [req.params.qrCode]
    );

    if (assets.length === 0) {
      return res.status(404).json({ error: 'Ativo não encontrado' });
    }

    const asset = assets[0];
    const [photos] = await db.query('SELECT id, asset_id, mime_type, caption, uploaded_at, photo_data FROM asset_photos WHERE asset_id = ? ORDER BY uploaded_at', [asset.id]);
    const [maintenance] = await db.query('SELECT * FROM maintenance_history WHERE asset_id = ? ORDER BY date DESC', [asset.id]);

    asset.photos = processPhotos(photos);
    asset.maintenanceHistory = maintenance;

    res.json(asset);
  } catch (error) {
    console.error('Error fetching asset by QR:', error);
    res.status(500).json({ error: 'Erro ao buscar ativo' });
  }
});

// POST create asset
router.post('/', async (req, res) => {
  try {
    const {
      qrCode, name, category, subcategory, description, serialNumber,
      patrimonyId, manufacturer, model, acquisitionDate, warrantyExpiry,
      purchasePrice, status, conditionRating, sectorId, location,
      custodianUserId, notes,
      // Novas colunas
      conta, categoria_inventario, bmp, componente, situacao, qtd,
      valor_atualizado, deprec_acumulada, valor_liquido
    } = req.body;

    if (!qrCode || !name || !category) {
      return res.status(400).json({ error: 'QR Code, nome e categoria são obrigatórios' });
    }

    // Check if QR code already exists
    const [existing] = await db.query('SELECT id FROM assets WHERE qr_code = ?', [qrCode]);
    if (existing.length > 0) {
      return res.status(400).json({ error: 'QR Code já cadastrado' });
    }

    const id = uuidv4();
    await db.query(
      `INSERT INTO assets (
        id, qr_code, name, category, subcategory, description, serial_number,
        patrimony_id, manufacturer, model, acquisition_date, warranty_expiry,
        purchase_price, status, condition_rating, sector_id, location,
        custodian_user_id, notes,
        -- Novas colunas
        conta, categoria_inventario, bmp, componente, situacao, qtd,
        valor_atualizado, deprec_acumulada, valor_liquido
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        id, qrCode, name, category, subcategory || null, description || null,
        serialNumber || null, patrimonyId || null, manufacturer || null,
        model || null, acquisitionDate || null, warrantyExpiry || null,
        purchasePrice || null, status || 'Disponível', conditionRating || null,
        sectorId || null, location || null, custodianUserId || null, notes || null,
        // Novos valores
        conta || null, categoria_inventario || null, bmp || null, componente || null,
        situacao || null, qtd || 1, valor_atualizado || null, deprec_acumulada || null,
        valor_liquido || null
      ]
    );

    const [rows] = await db.query('SELECT * FROM assets WHERE id = ?', [id]);
    const asset = rows[0];
    asset.photos = [];
    asset.maintenanceHistory = [];

    res.status(201).json(asset);
  } catch (error) {
    console.error('Error creating asset:', error);
    res.status(500).json({ error: 'Erro ao criar ativo' });
  }
});

// PUT update asset
router.put('/:id', async (req, res) => {
  try {
    const {
      qrCode, name, category, subcategory, description, serialNumber,
      patrimonyId, manufacturer, model, acquisitionDate, warrantyExpiry,
      purchasePrice, status, conditionRating, sectorId, location,
      custodianUserId, notes,
      // Novas colunas
      conta, categoria_inventario, bmp, componente, situacao, qtd,
      valor_atualizado, deprec_acumulada, valor_liquido
    } = req.body;

    if (!qrCode || !name || !category) {
      return res.status(400).json({ error: 'QR Code, nome e categoria são obrigatórios' });
    }

    // Check if QR code is used by another asset
    const [existing] = await db.query(
      'SELECT id FROM assets WHERE qr_code = ? AND id != ?',
      [qrCode, req.params.id]
    );
    if (existing.length > 0) {
      return res.status(400).json({ error: 'QR Code já cadastrado' });
    }

    const [result] = await db.query(
      `UPDATE assets SET
        qr_code = ?, name = ?, category = ?, subcategory = ?, description = ?,
        serial_number = ?, patrimony_id = ?, manufacturer = ?, model = ?,
        acquisition_date = ?, warranty_expiry = ?, purchase_price = ?,
        status = ?, condition_rating = ?, sector_id = ?, location = ?,
        custodian_user_id = ?, notes = ?,
        -- Novas colunas
        conta = ?, categoria_inventario = ?, bmp = ?, componente = ?, situacao = ?, qtd = ?,
        valor_atualizado = ?, deprec_acumulada = ?, valor_liquido = ?
       WHERE id = ?`,
      [
        qrCode, name, category, subcategory || null, description || null,
        serialNumber || null, patrimonyId || null, manufacturer || null,
        model || null, acquisitionDate || null, warrantyExpiry || null,
        purchasePrice || null, status || 'Disponível', conditionRating || null,
        sectorId || null, location || null, custodianUserId || null,
        notes || null,
        // Novos valores
        conta || null, categoria_inventario || null, bmp || null, componente || null,
        situacao || null, qtd || 1, valor_atualizado || null, deprec_acumulada || null,
        valor_liquido || null, 
        req.params.id
      ]
    );

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Ativo não encontrado' });
    }

    const [assets] = await db.query('SELECT * FROM assets WHERE id = ?', [req.params.id]);
    const asset = assets[0];

    const [photos] = await db.query('SELECT id, asset_id, mime_type, caption, uploaded_at, photo_data FROM asset_photos WHERE asset_id = ?', [asset.id]);
    const [maintenance] = await db.query('SELECT * FROM maintenance_history WHERE asset_id = ? ORDER BY date DESC', [asset.id]);

    asset.photos = processPhotos(photos);
    asset.maintenanceHistory = maintenance;

    res.json(asset);
  } catch (error) {
    console.error('Error updating asset:', error);
    res.status(500).json({ error: 'Erro ao atualizar ativo' });
  }
});

// DELETE asset
router.delete('/:id', async (req, res) => {
  try {
    // Check if asset is in custody or inventory
    const [custody] = await db.query(
      'SELECT COUNT(*) as count FROM custody_assets WHERE asset_id = ?',
      [req.params.id]
    );

    if (custody[0].count > 0) {
      return res.status(400).json({
        error: 'Não é possível excluir ativo com cautelas associadas'
      });
    }

    const [result] = await db.query('DELETE FROM assets WHERE id = ?', [req.params.id]);

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Ativo não encontrado' });
    }

    res.json({ message: 'Ativo excluído com sucesso' });
  } catch (error) {
    console.error('Error deleting asset:', error);
    res.status(500).json({ error: 'Erro ao excluir ativo' });
  }
});

// POST add photo to asset
router.post('/:id/photos', upload.single('photo'), async (req, res) => {
  try {
    if (!req.file) {
      return res.status(400).json({ error: 'Nenhuma foto foi enviada' });
    }

    const photoId = uuidv4();
    const { buffer, mimetype } = req.file;
    const caption = req.body.caption || null;

    await db.query(
      'INSERT INTO asset_photos (id, asset_id, photo_data, mime_type, caption) VALUES (?, ?, ?, ?, ?)',
      [photoId, req.params.id, buffer, mimetype, caption]
    );

    const [rows] = await db.query('SELECT id, asset_id, mime_type, caption, uploaded_at, photo_data FROM asset_photos WHERE id = ?', [photoId]);
    res.status(201).json(processPhotos(rows)[0]);
  } catch (error) {
    console.error('Error adding photo:', error);
    res.status(500).json({ error: 'Erro ao adicionar foto' });
  }
});

// DELETE photo from asset
router.delete('/:id/photos/:photoId', async (req, res) => {
  try {
    const [result] = await db.query(
      'DELETE FROM asset_photos WHERE id = ? AND asset_id = ?',
      [req.params.photoId, req.params.id]
    );

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Foto não encontrada' });
    }

    res.json({ message: 'Foto excluída com sucesso' });
  } catch (error) {
    console.error('Error deleting photo:', error);
    res.status(500).json({ error: 'Erro ao excluir foto' });
  }
});

// POST add maintenance record
router.post('/:id/maintenance', async (req, res) => {
  try {
    const { date, description, performedBy, cost } = req.body;

    if (!date || !description) {
      return res.status(400).json({ error: 'Data e descrição são obrigatórios' });
    }

    const maintenanceId = uuidv4();
    await db.query(
      'INSERT INTO maintenance_history (id, asset_id, date, description, performed_by, cost) VALUES (?, ?, ?, ?, ?, ?)',
      [maintenanceId, req.params.id, date, description, performedBy || null, cost || null]
    );

    const [rows] = await db.query('SELECT * FROM maintenance_history WHERE id = ?', [maintenanceId]);
    res.status(201).json(rows[0]);
  } catch (error) {
    console.error('Error adding maintenance:', error);
    res.status(500).json({ error: 'Erro ao adicionar manutenção' });
  }
});

// DELETE maintenance record
router.delete('/:id/maintenance/:maintenanceId', async (req, res) => {
  try {
    const [result] = await db.query(
      'DELETE FROM maintenance_history WHERE id = ? AND asset_id = ?',
      [req.params.maintenanceId, req.params.id]
    );

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Registro de manutenção não encontrado' });
    }

    res.json({ message: 'Registro de manutenção excluído com sucesso' });
  } catch (error) {
    console.error('Error deleting maintenance:', error);
    res.status(500).json({ error: 'Erro ao excluir manutenção' });
  }
});

// Helper: Generate next QR code
router.get('/utils/next-qr-code', async (req, res) => {
  try {
    const [rows] = await db.query(
      "SELECT qr_code FROM assets WHERE qr_code LIKE 'SGAITI-%' ORDER BY qr_code DESC LIMIT 1"
    );

    let nextNumber = 1;
    if (rows.length > 0) {
      const lastQr = rows[0].qr_code;
      const match = lastQr.match(/SGAITI-(\d+)/);
      if (match) {
        nextNumber = parseInt(match[1]) + 1;
      }
    }

    const nextQrCode = `SGAITI-${String(nextNumber).padStart(4, '0')}`;
    res.json({ qrCode: nextQrCode });
  } catch (error) {
    console.error('Error generating QR code:', error);
    res.status(500).json({ error: 'Erro ao gerar QR code' });
  }
});

module.exports = router;
