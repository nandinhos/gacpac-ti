require('dotenv').config();
const mysql = require('mysql2/promise');

async function fixData() {
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

    // Check current status
    const [usersStatus] = await connection.query('SELECT COUNT(*) as total, SUM(is_active) as active FROM users');
    console.log(`\nStatus atual: ${usersStatus[0].total} usuários, ${usersStatus[0].active} ativos`);

    // Check assets with custodians
    const [assetsStatus] = await connection.query(`
      SELECT COUNT(*) as total,
             SUM(CASE WHEN custodian_user_id IS NOT NULL THEN 1 ELSE 0 END) as with_custodian
      FROM assets
      WHERE status != 'Baixado'
    `);
    console.log(`Ativos: ${assetsStatus[0].total} total, ${assetsStatus[0].with_custodian} com custódio\n`);

    // Fix 1: Activate all users except one (Carla Dias)
    console.log('1. Ativando usuários...');
    const [activateResult] = await connection.query(`
      UPDATE users
      SET is_active = 1
      WHERE name != 'Carla Dias'
    `);
    console.log(`✓ ${activateResult.affectedRows} usuários ativados`);

    // Fix 2: Show assets that should have custodians but don't
    console.log('\n2. Verificando ativos sem custódio na ATI...');
    const [assetsWithoutCustodian] = await connection.query(`
      SELECT a.id, a.name, a.qr_code, s.name as sector_name
      FROM assets a
      INNER JOIN sectors s ON a.sector_id = s.id
      WHERE a.custodian_user_id IS NULL
        AND a.status = 'Em Uso'
        AND s.name = 'ATI'
    `);

    if (assetsWithoutCustodian.length > 0) {
      console.log(`⚠️  Encontrados ${assetsWithoutCustodian.length} ativos "Em Uso" na ATI sem custódio:`);
      assetsWithoutCustodian.forEach(asset => {
        console.log(`   - ${asset.qr_code}: ${asset.name}`);
      });
    } else {
      console.log('✓ Todos os ativos "Em Uso" têm custódios');
    }

    // Fix 3: Ensure asset custodians are correct
    console.log('\n3. Verificando consistência dos custódios...');
    const [inconsistentAssets] = await connection.query(`
      SELECT a.id, a.name, a.qr_code, a.status, u.name as custodian_name, u.is_active
      FROM assets a
      INNER JOIN users u ON a.custodian_user_id = u.id
      WHERE a.status != 'Em Uso' OR u.is_active = 0
    `);

    if (inconsistentAssets.length > 0) {
      console.log(`⚠️  Encontrados ${inconsistentAssets.length} ativos com custódios inconsistentes:`);
      inconsistentAssets.forEach(asset => {
        console.log(`   - ${asset.qr_code}: ${asset.name} (Status: ${asset.status}, Custódio ativo: ${asset.is_active})`);
      });

      // Fix: Remove custodians from assets that are not "Em Uso" or have inactive custodians
      const [fixResult] = await connection.query(`
        UPDATE assets a
        INNER JOIN users u ON a.custodian_user_id = u.id
        SET a.custodian_user_id = NULL, a.status = 'Disponível'
        WHERE a.status != 'Em Uso' OR u.is_active = 0
      `);
      console.log(`✓ ${fixResult.affectedRows} ativos corrigidos`);
    } else {
      console.log('✓ Todos os custódios estão consistentes');
    }

    // Summary
    console.log('\n═══════════════════════════════════════════════════');
    console.log('📊 RESUMO FINAL');
    console.log('═══════════════════════════════════════════════════');

    const [finalUsers] = await connection.query(`
      SELECT name, \`rank\`, is_active,
             (SELECT COUNT(*) FROM assets WHERE custodian_user_id = users.id AND status != 'Baixado') as asset_count
      FROM users
      ORDER BY is_active DESC, asset_count DESC
    `);

    console.log('\n👥 Usuários:');
    finalUsers.forEach(user => {
      const status = user.is_active ? '✓ Ativo' : '✗ Inativo';
      console.log(`   ${status} | ${user.rank} ${user.name} | ${user.asset_count} ativos`);
    });

    const [finalSummary] = await connection.query(`
      SELECT
        COUNT(DISTINCT u.id) as total_users,
        SUM(u.is_active) as active_users,
        COUNT(DISTINCT a.id) as total_assets,
        SUM(CASE WHEN a.custodian_user_id IS NOT NULL THEN 1 ELSE 0 END) as assets_with_custodian
      FROM users u, assets a
      WHERE a.status != 'Baixado'
    `);

    console.log('\n📈 Estatísticas:');
    console.log(`   Usuários ativos: ${finalSummary[0].active_users}/${finalSummary[0].total_users}`);
    console.log(`   Ativos com custódio: ${finalSummary[0].assets_with_custodian}/${finalSummary[0].total_assets}`);

    console.log('\n✅ Correção concluída com sucesso!\n');

  } catch (error) {
    console.error('Error fixing data:', error);
    process.exit(1);
  } finally {
    if (connection) {
      await connection.end();
    }
  }
}

fixData();
