require('dotenv').config();
const mysql = require('mysql2/promise');

async function updateAssets() {
  let connection;
  try {
    console.log('Connecting to database...');
    connection = await mysql.createConnection({
      host: process.env.DB_HOST || 'localhost',
      port: process.env.DB_PORT || 33060,
      user: process.env.DB_USER || 'sgaiti_user',
      password: process.env.DB_PASSWORD || 'sgaiti_pass',
      database: process.env.DB_NAME || 'sgaiti_db'
    });

    console.log('✓ Connected to database');

    // Get Almoxarifado TI sector ID
    const [almoxSectors] = await connection.query(
      "SELECT id FROM sectors WHERE name = 'Almoxarifado TI' LIMIT 1"
    );

    if (almoxSectors.length === 0) {
      console.error('Almoxarifado TI sector not found!');
      return;
    }

    const almoxId = almoxSectors[0].id;
    console.log(`Almoxarifado TI ID: ${almoxId}`);

    // Move assets without custodian to Almoxarifado (except maintenance and decommissioned)
    const [result] = await connection.query(
      `UPDATE assets
       SET sector_id = ?, status = 'Disponível'
       WHERE custodian_user_id IS NULL
         AND status NOT IN ('Manutenção', 'Baixado')
         AND sector_id != ?`,
      [almoxId, almoxId]
    );

    console.log(`✓ ${result.affectedRows} ativos movidos para Almoxarifado TI`);

    // Show summary
    const [summary] = await connection.query(`
      SELECT
        s.name as setor,
        COUNT(*) as total_ativos,
        SUM(CASE WHEN a.custodian_user_id IS NOT NULL THEN 1 ELSE 0 END) as com_custodia,
        SUM(CASE WHEN a.custodian_user_id IS NULL THEN 1 ELSE 0 END) as sem_custodia
      FROM assets a
      INNER JOIN sectors s ON a.sector_id = s.id
      WHERE a.status != 'Baixado'
      GROUP BY s.name
      ORDER BY total_ativos DESC
    `);

    console.log('\n📊 Resumo por Setor:');
    console.log('─────────────────────────────────────────────────────');
    summary.forEach(row => {
      console.log(`${row.setor.padEnd(30)} │ ${row.total_ativos} ativos │ ${row.com_custodia} com custódia │ ${row.sem_custodia} sem custódia`);
    });
    console.log('─────────────────────────────────────────────────────');

    // Show assets by user
    const [userAssets] = await connection.query(`
      SELECT
        u.name,
        u.rank,
        COUNT(a.id) as total_ativos,
        GROUP_CONCAT(a.name ORDER BY a.name SEPARATOR ', ') as ativos
      FROM users u
      INNER JOIN assets a ON u.id = a.custodian_user_id
      WHERE a.status != 'Baixado'
      GROUP BY u.id, u.name, u.rank
      ORDER BY total_ativos DESC
    `);

    console.log('\n👥 Ativos por Militar:');
    console.log('─────────────────────────────────────────────────────');
    userAssets.forEach(row => {
      console.log(`\n${row.rank} ${row.name} - ${row.total_ativos} ativos:`);
      console.log(`  ${row.ativos}`);
    });

  } catch (error) {
    console.error('Error updating assets:', error);
    process.exit(1);
  } finally {
    if (connection) {
      await connection.end();
    }
  }
}

updateAssets();
