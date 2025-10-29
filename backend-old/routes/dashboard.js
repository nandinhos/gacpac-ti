const express = require('express');
const router = express.Router();
const db = require('../config/database');

// GET dashboard statistics
router.get('/stats', async (req, res) => {
  try {
    // Total assets by status
    const [assetStats] = await db.query(`
      SELECT
        COUNT(*) as total,
        SUM(CASE WHEN status = 'Em Uso' THEN 1 ELSE 0 END) as em_uso,
        SUM(CASE WHEN status = 'Disponível' THEN 1 ELSE 0 END) as disponivel,
        SUM(CASE WHEN status = 'Manutenção' THEN 1 ELSE 0 END) as manutencao,
        SUM(CASE WHEN status = 'Baixado' THEN 1 ELSE 0 END) as baixado
      FROM assets
    `);

    // Assets by category
    const [categoryStats] = await db.query(`
      SELECT category, COUNT(*) as count
      FROM assets
      GROUP BY category
      ORDER BY count DESC
    `);

    // Active custody logs
    const [activeCustody] = await db.query(`
      SELECT COUNT(*) as count
      FROM custody_logs
      WHERE checkin_date IS NULL
    `);

    // Active inventories
    const [activeInventories] = await db.query(`
      SELECT COUNT(*) as count
      FROM inventory_records
      WHERE status IN ('Em Andamento', 'Reaberto')
    `);

    // Total users by sector
    const [userStats] = await db.query(`
      SELECT COUNT(*) as total,
             SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active
      FROM users
    `);

    // Total sectors
    const [sectorStats] = await db.query(`
      SELECT COUNT(*) as total FROM sectors
    `);

    // Recent assets (last 5)
    const [recentAssets] = await db.query(`
      SELECT id, name, qr_code, category, created_at
      FROM assets
      ORDER BY created_at DESC
      LIMIT 5
    `);

    // Recent custody logs (last 5)
    const [recentCustody] = await db.query(`
      SELECT cl.id, cl.cautela_number, cl.checkout_date, cl.checkin_date,
             u.name as user_name, u.rank as user_rank
      FROM custody_logs cl
      INNER JOIN users u ON cl.user_id = u.id
      ORDER BY cl.checkout_date DESC
      LIMIT 5
    `);

    // Assets needing maintenance (warranty expiring in 30 days)
    const [maintenanceNeeded] = await db.query(`
      SELECT COUNT(*) as count
      FROM assets
      WHERE warranty_expiry IS NOT NULL
        AND warranty_expiry <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
        AND warranty_expiry >= CURDATE()
    `);

    res.json({
      assets: {
        total: assetStats[0].total,
        byStatus: {
          emUso: assetStats[0].em_uso,
          disponivel: assetStats[0].disponivel,
          manutencao: assetStats[0].manutencao,
          baixado: assetStats[0].baixado
        },
        byCategory: categoryStats.reduce((acc, item) => {
          acc[item.category] = item.count;
          return acc;
        }, {}),
        maintenanceNeeded: maintenanceNeeded[0].count
      },
      custody: {
        active: activeCustody[0].count
      },
      inventory: {
        active: activeInventories[0].count
      },
      users: {
        total: userStats[0].total,
        active: userStats[0].active
      },
      sectors: {
        total: sectorStats[0].total
      },
      recent: {
        assets: recentAssets,
        custody: recentCustody
      }
    });
  } catch (error) {
    console.error('Error fetching dashboard stats:', error);
    res.status(500).json({ error: 'Erro ao buscar estatísticas' });
  }
});

module.exports = router;
