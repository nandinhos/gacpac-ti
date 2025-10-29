require('dotenv').config();
const mysql = require('mysql2/promise');
const { v4: uuidv4 } = require('uuid');

// Mock data based on TypeScript mockData.ts
const sectors = [
  { name: 'CHF', description: 'Chefia' },
  { name: 'ATI', description: 'Assessoria de Tecnologia da Informação' },
  { name: 'AIT', description: 'Assessoria de Inteligência' },
  { name: 'SEC', description: 'Secretaria' },
  { name: 'ALOG', description: 'Assessoria Logística' },
  { name: 'SFI', description: 'Seção Financeira' },
  { name: 'SAD', description: 'Seção Administrativa' },
  { name: 'STEC', description: 'Seção Técnica' },
  { name: 'SCP-SIS', description: 'Seção de Coordenação de Projetos e Sistemas' },
  { name: 'Almoxarifado TI', description: 'Depósito de material de TI' }
];

const users = [
  { name: 'Ricardo Goulart', rank: 'Coronel Aviador', militaryId: '111.222.333-01', sectorIndex: 0, active: true },
  { name: 'Beatriz Almeida', rank: 'Major Especialista', militaryId: '222.333.444-02', sectorIndex: 1, active: true },
  { name: 'Lucas Martins', rank: 'Capitão de Infantaria', militaryId: '333.444.555-03', sectorIndex: 2, active: true },
  { name: 'Juliana Costa', rank: 'Primeiro-Tenente Intendente', militaryId: '444.555.666-04', sectorIndex: 5, active: true },
  { name: 'Fernando Oliveira', rank: 'Segundo-Sargento BCT', militaryId: '555.666.777-05', sectorIndex: 1, active: true },
  { name: 'Patrícia Souza', rank: 'Terceiro-Sargento SAD', militaryId: '666.777.888-06', sectorIndex: 6, active: true },
  { name: 'Gustavo Pereira', rank: 'Cabo', militaryId: '777.888.999-07', sectorIndex: 4, active: true },
  { name: 'Carla Dias', rank: 'Soldado-de-Primeira-Classe', militaryId: '888.999.000-08', sectorIndex: 3, active: false },
  { name: 'Marcos Lima', rank: 'Suboficial BCO', militaryId: '999.000.111-09', sectorIndex: 7, active: true },
  { name: 'Helena Santos', rank: 'Primeiro-Tenente Analista de Sistemas', militaryId: '000.111.222-10', sectorIndex: 8, active: true },
  { name: 'Sérgio Ramos', rank: 'Cabo', militaryId: '123.123.123-11', sectorIndex: 9, active: true },
  { name: 'Rafael Andrade', rank: 'Terceiro-Sargento BCT', militaryId: '234.234.234-12', sectorIndex: 1, active: true },
  { name: 'Mariana Campos', rank: 'Cabo', militaryId: '345.345.345-13', sectorIndex: 1, active: true },
  { name: 'Vanessa Rocha', rank: 'Primeiro-Tenente Estagiária', militaryId: '456.456.456-14', sectorIndex: 1, active: true }
];

const assets = [
  { qrCode: 'SGAITI-0001', patrimonyId: 'FAB-1001', serialNumber: 'BR-A1B2C3D', category: 'Computação', name: 'Desktop Dell Vostro 3888', sectorIndex: 0, custodianIndex: 0, status: 'Em Uso', acquisitionDate: '2023-02-10', warrantyExpiry: '2026-02-10' },
  { qrCode: 'SGAITI-0002', patrimonyId: 'FAB-1002', serialNumber: 'US-E4F5G6H', category: 'Computação', name: 'Notebook Dell Latitude 7420', sectorIndex: 1, custodianIndex: 1, status: 'Em Uso', acquisitionDate: '2023-05-15', warrantyExpiry: '2026-05-15' },
  { qrCode: 'SGAITI-0003', patrimonyId: 'FAB-2001', serialNumber: 'SRV-I7J8K9L', category: 'Computação', name: 'Servidor Dell PowerEdge R750', sectorIndex: 1, custodianIndex: null, status: 'Em Uso', acquisitionDate: '2022-08-20' },
  { qrCode: 'SGAITI-0004', serialNumber: 'MON-M1N2O3P', category: 'Periféricos', name: 'Monitor Gamer LG UltraGear 27"', sectorIndex: 2, custodianIndex: 2, status: 'Em Uso', acquisitionDate: '2023-11-01' },
  { qrCode: 'SGAITI-0005', patrimonyId: 'FAB-3001', serialNumber: 'PRT-Q4R5S6T', category: 'Periféricos', name: 'Impressora Multifuncional Brother MFC-L8900CDW', sectorIndex: 3, custodianIndex: null, status: 'Em Uso', acquisitionDate: '2021-09-05' },
  { qrCode: 'SGAITI-0006', serialNumber: 'SWT-U7V8W9X', category: 'Comunicações', name: 'Switch Cisco Catalyst 9200 24p', sectorIndex: 1, custodianIndex: null, status: 'Em Uso', acquisitionDate: '2022-10-10' },
  { qrCode: 'SGAITI-0007', patrimonyId: 'FAB-1003', serialNumber: 'BR-Y1Z2A3B', category: 'Computação', name: 'Desktop HP EliteDesk 800 G9', sectorIndex: 5, custodianIndex: 3, status: 'Em Uso', acquisitionDate: '2023-03-12', warrantyExpiry: '2026-03-12' },
  { qrCode: 'SGAITI-0008', patrimonyId: 'FAB-1004', serialNumber: 'CN-C4D5E6F', category: 'Computação', name: 'Notebook Lenovo ThinkPad T14 Gen 3', sectorIndex: 7, custodianIndex: 8, status: 'Em Uso', acquisitionDate: '2023-07-20', warrantyExpiry: '2026-07-20' },
  { qrCode: 'SGAITI-0009', serialNumber: 'RTR-G7H8I9J', category: 'Comunicações', name: 'Roteador Wireless TP-Link Archer AX50', sectorIndex: 4, custodianIndex: 6, status: 'Em Uso', acquisitionDate: '2024-01-15' },
  { qrCode: 'SGAITI-0010', patrimonyId: 'FAB-4001', serialNumber: 'UPS-K1L2M3N', category: 'Energia', name: 'Nobreak APC Smart-UPS 3000VA', sectorIndex: 1, custodianIndex: null, status: 'Manutenção', acquisitionDate: '2020-06-30' },
  { qrCode: 'SGAITI-0011', patrimonyId: 'FAB-1005', serialNumber: 'TAB-O4P5Q6R', category: 'Computação', name: 'Tablet Samsung Galaxy Tab S8', sectorIndex: 8, custodianIndex: 9, status: 'Em Uso', acquisitionDate: '2023-09-01', warrantyExpiry: '2024-09-01' },
  { qrCode: 'SGAITI-0012', patrimonyId: 'FAB-5001', serialNumber: 'PRJ-S7T8U9V', category: 'Outros Ativos de TI', name: 'Projetor Epson PowerLite E20', sectorIndex: 6, custodianIndex: 5, status: 'Em Uso', acquisitionDate: '2022-04-18' },
  { qrCode: 'SGAITI-0013', serialNumber: 'SCN-W1X2Y3Z', category: 'Periféricos', name: 'Scanner de Mesa Fujitsu ScanSnap iX1600', sectorIndex: 9, custodianIndex: null, status: 'Disponível', acquisitionDate: '2023-10-05' },
  { qrCode: 'SGAITI-0014', serialNumber: 'DCK-A2B3C4D', category: 'Periféricos', name: 'Docking Station Dell WD19S', sectorIndex: 1, custodianIndex: 1, status: 'Em Uso', acquisitionDate: '2023-05-15' },
  { qrCode: 'SGAITI-0015', serialNumber: 'CAM-E5F6G7H', category: 'Periféricos', name: 'Webcam Logitech C920 Pro HD', sectorIndex: 0, custodianIndex: 0, status: 'Em Uso', acquisitionDate: '2023-02-10' },
  { qrCode: 'SGAITI-0016', serialNumber: 'KBD-I8J9K1L', category: 'Periféricos', name: 'Teclado Mecânico Redragon Kumara K552', sectorIndex: 9, custodianIndex: null, status: 'Disponível', acquisitionDate: '2024-02-20' },
  { qrCode: 'SGAITI-0017', serialNumber: 'MSE-M2N3O4P', category: 'Periféricos', name: 'Mouse Logitech MX Master 3S', sectorIndex: 9, custodianIndex: null, status: 'Disponível', acquisitionDate: '2024-02-20' },
  { qrCode: 'SGAITI-0018', patrimonyId: 'FAB-2002', serialNumber: 'NAS-Q5R6S7T', category: 'Computação', name: 'Servidor de Backup Synology DiskStation DS923+', sectorIndex: 1, custodianIndex: null, status: 'Baixado', acquisitionDate: '2019-01-15' },
  { qrCode: 'SGAITI-0019', serialNumber: 'MON-DELL-U2421E', category: 'Periféricos', name: 'Monitor Dell UltraSharp 24"', sectorIndex: 1, custodianIndex: 1, status: 'Em Uso', acquisitionDate: '2023-05-15' },
  { qrCode: 'SGAITI-0020', serialNumber: 'KBD-DELL-KB216', category: 'Periféricos', name: 'Teclado Dell KB216', sectorIndex: 1, custodianIndex: 1, status: 'Em Uso', acquisitionDate: '2023-05-15' },
  { qrCode: 'SGAITI-0021', serialNumber: 'MSE-DELL-MS116', category: 'Periféricos', name: 'Mouse Dell MS116', sectorIndex: 1, custodianIndex: 1, status: 'Em Uso', acquisitionDate: '2023-05-15' },
  { qrCode: 'SGAITI-0022', patrimonyId: 'FAB-1010', serialNumber: 'DT-DELL-OPT7090', category: 'Computação', name: 'Desktop Dell Optiplex 7090', sectorIndex: 1, custodianIndex: 4, status: 'Em Uso', acquisitionDate: '2023-01-20' },
  { qrCode: 'SGAITI-0023', serialNumber: 'MON-DELL-P2422H-1', category: 'Periféricos', name: 'Monitor Dell 24"', sectorIndex: 1, custodianIndex: 4, status: 'Em Uso', acquisitionDate: '2023-01-20' },
  { qrCode: 'SGAITI-0024', serialNumber: 'MON-DELL-P2422H-2', category: 'Periféricos', name: 'Monitor Dell 24"', sectorIndex: 1, custodianIndex: 4, status: 'Em Uso', acquisitionDate: '2023-01-20' },
  { qrCode: 'SGAITI-0025', serialNumber: 'KBD-DELL-KB216-2', category: 'Periféricos', name: 'Teclado Dell KB216', sectorIndex: 1, custodianIndex: 4, status: 'Em Uso', acquisitionDate: '2023-01-20' },
  { qrCode: 'SGAITI-0026', serialNumber: 'MSE-DELL-MS116-2', category: 'Periféricos', name: 'Mouse Dell MS116', sectorIndex: 1, custodianIndex: 4, status: 'Em Uso', acquisitionDate: '2023-01-20' },
  { qrCode: 'SGAITI-0027', serialNumber: 'HDS-LOGI-H390', category: 'Periféricos', name: 'Headset Logitech H390', sectorIndex: 1, custodianIndex: 4, status: 'Em Uso', acquisitionDate: '2023-01-20' },
  { qrCode: 'SGAITI-0028', patrimonyId: 'FAB-1011', serialNumber: 'DT-DELL-VOS3888', category: 'Computação', name: 'Desktop Dell Vostro 3888', sectorIndex: 1, custodianIndex: 11, status: 'Em Uso', acquisitionDate: '2023-06-10' },
  { qrCode: 'SGAITI-0029', serialNumber: 'MON-LG-22MP410', category: 'Periféricos', name: 'Monitor LG 22"', sectorIndex: 1, custodianIndex: 11, status: 'Em Uso', acquisitionDate: '2023-06-10' },
  { qrCode: 'SGAITI-0030', serialNumber: 'KBD-LOGI-K120', category: 'Periféricos', name: 'Teclado Logitech K120', sectorIndex: 1, custodianIndex: 11, status: 'Em Uso', acquisitionDate: '2023-06-10' },
  { qrCode: 'SGAITI-0031', serialNumber: 'MSE-LOGI-M90', category: 'Periféricos', name: 'Mouse Logitech M90', sectorIndex: 1, custodianIndex: 11, status: 'Em Uso', acquisitionDate: '2023-06-10' },
  { qrCode: 'SGAITI-0032', patrimonyId: 'FAB-1012', serialNumber: 'NB-DELL-LAT5420', category: 'Computação', name: 'Notebook Dell Latitude 5420', sectorIndex: 1, custodianIndex: 12, status: 'Em Uso', acquisitionDate: '2023-08-01' },
  { qrCode: 'SGAITI-0033', serialNumber: 'MON-DELL-P2422H-3', category: 'Periféricos', name: 'Monitor Dell 24"', sectorIndex: 1, custodianIndex: 12, status: 'Em Uso', acquisitionDate: '2023-08-01' },
  { qrCode: 'SGAITI-0034', serialNumber: 'DCK-DELL-WD19', category: 'Periféricos', name: 'Docking Station Dell WD19', sectorIndex: 1, custodianIndex: 12, status: 'Em Uso', acquisitionDate: '2023-08-01' },
  { qrCode: 'SGAITI-0035', serialNumber: 'KBD-DELL-KM5221W', category: 'Periféricos', name: 'Teclado Externo Dell (sem fio)', sectorIndex: 1, custodianIndex: 12, status: 'Em Uso', acquisitionDate: '2023-08-01' },
  { qrCode: 'SGAITI-0036', serialNumber: 'MSE-DELL-KM5221W', category: 'Periféricos', name: 'Mouse Externo Dell (sem fio)', sectorIndex: 1, custodianIndex: 12, status: 'Em Uso', acquisitionDate: '2023-08-01' },
  { qrCode: 'SGAITI-0037', patrimonyId: 'FAB-1013', serialNumber: 'NB-LENOVO-T14', category: 'Computação', name: 'Notebook Lenovo ThinkPad T14', sectorIndex: 1, custodianIndex: 13, status: 'Em Uso', acquisitionDate: '2024-02-15' },
  { qrCode: 'SGAITI-0038', serialNumber: 'MSE-LOGI-M185', category: 'Periféricos', name: 'Mouse sem fio Logitech M185', sectorIndex: 1, custodianIndex: 13, status: 'Em Uso', acquisitionDate: '2024-02-15' },
  { qrCode: 'SGAITI-0039', serialNumber: 'PRJ-BENQ-GV30', category: 'Outros Ativos de TI', name: 'Projetor Portátil BenQ', sectorIndex: 9, custodianIndex: null, status: 'Disponível', acquisitionDate: '2023-12-01' },
  { qrCode: 'SGAITI-0040', serialNumber: 'CAM-CANON-T7I', category: 'Outros Ativos de TI', name: 'Câmera DSLR Canon T7i com lente 18-55mm', sectorIndex: 9, custodianIndex: null, status: 'Disponível', acquisitionDate: '2022-11-11' },
  { qrCode: 'SGAITI-0041', patrimonyId: 'FAB-3005', serialNumber: 'PRT-HP-M428FDW', category: 'Periféricos', name: 'Impressora de Rede HP LaserJet Pro M428fdw', sectorIndex: 1, custodianIndex: null, status: 'Em Uso', acquisitionDate: '2022-09-20' },
  { qrCode: 'SGAITI-0042', serialNumber: 'SCN-BROTHER-ADS2700W', category: 'Periféricos', name: 'Scanner de Rede Brother ADS-2700W', sectorIndex: 1, custodianIndex: null, status: 'Em Uso', acquisitionDate: '2023-03-30' }
];

const custodyLogs = [
  {
    cautela_number: '001/GAC-PAC/2024',
    userIndex: 1, // Beatriz Almeida
    assetIndices: [1, 3, 13], // Notebook Dell, Monitor LG, Scanner Fujitsu
    checkout_date: '2024-01-15',
    checkin_date: '2024-02-15',
    notes: 'Empréstimo para projeto de desenvolvimento'
  },
  {
    cautela_number: '002/GAC-PAC/2024',
    userIndex: 2, // Lucas Martins
    assetIndices: [0, 4, 14], // Desktop Dell, Impressora Brother, Docking Station Dell
    checkout_date: '2024-02-01',
    checkin_date: null, // Ainda ativo
    notes: 'Equipamentos para trabalho remoto'
  },
  {
    cautela_number: '003/GAC-PAC/2024',
    userIndex: 4, // Fernando Oliveira
    assetIndices: [7, 19], // Notebook Lenovo, Monitor Dell U2421E
    checkout_date: '2024-02-20',
    checkin_date: null, // Ainda ativo
    notes: 'Equipamentos para desenvolvimento de software'
  },
  {
    cautela_number: '004/GAC-PAC/2024',
    userIndex: 8, // Marcos Lima
    assetIndices: [6, 23, 24, 25], // Desktop HP, Monitor Dell 24", Teclado Dell, Mouse Dell
    checkout_date: '2024-03-01',
    checkin_date: '2024-03-15',
    notes: 'Equipamentos temporários para manutenção'
  },
  {
    cautela_number: '005/GAC-PAC/2024',
    userIndex: 9, // Helena Santos
    assetIndices: [10, 32, 33], // Tablet Samsung, Monitor Dell 24", Docking Station Dell
    checkout_date: '2024-03-10',
    checkin_date: null, // Ainda ativo
    notes: 'Equipamentos para desenvolvimento mobile'
  },
  {
    cautela_number: '006/GAC-PAC/2024',
    userIndex: 11, // Rafael Andrade
    assetIndices: [27, 28, 29, 30], // Desktop Dell Vostro, Monitor LG, Teclado Logitech, Mouse Logitech
    checkout_date: '2024-03-20',
    checkin_date: null, // Ainda ativo
    notes: 'Equipamentos para trabalho administrativo'
  },
  {
    cautela_number: '007/GAC-PAC/2024',
    userIndex: 12, // Mariana Campos
    assetIndices: [31, 34, 35], // Notebook Dell Latitude, Teclado Externo Dell, Mouse Externo Dell
    checkout_date: '2024-04-01',
    checkin_date: null, // Ainda ativo
    notes: 'Equipamentos para projeto especial'
  },
  {
    cautela_number: '008/GAC-PAC/2024',
    userIndex: 13, // Vanessa Rocha
    assetIndices: [36, 37], // Notebook Lenovo ThinkPad, Mouse Logitech M185
    checkout_date: '2024-04-10',
    checkin_date: null, // Ainda ativo
    notes: 'Equipamentos para estágio'
  },
  {
    cautela_number: '009/GAC-PAC/2023',
    userIndex: 0, // Ricardo Goulart
    assetIndices: [2, 5, 9], // Servidor Dell, Switch Cisco, Nobreak APC
    checkout_date: '2023-12-01',
    checkin_date: '2023-12-20',
    notes: 'Equipamentos para sala de servidores'
  },
  {
    cautela_number: '010/GAC-PAC/2023',
    userIndex: 3, // Juliana Costa
    assetIndices: [8, 11], // Roteador TP-Link, Projetor Epson
    checkout_date: '2023-11-15',
    checkin_date: '2023-12-01',
    notes: 'Equipamentos para apresentação'
  },
  {
    cautela_number: '011/GAC-PAC/2024',
    userIndex: 6, // Gustavo Pereira
    assetIndices: [15, 16], // Webcam Logitech, Teclado Mecânico Redragon
    checkout_date: '2024-01-05',
    checkin_date: null, // Ainda ativo
    notes: 'Equipamentos para laboratório'
  }
];

async function seed() {
  let connection;
  try {
    console.log('Connecting to database...');
    connection = await mysql.createConnection({
      host: process.env.DB_HOST || 'localhost',
      port: process.env.DB_PORT || 33060,
      user: process.env.DB_USER || 'sgaiti_user',
      password: process.env.DB_PASSWORD || 'sgaiti_pass',
      database: process.env.DB_NAME || 'sgaiti_db',
      multipleStatements: true
    });

    console.log('✓ Connected to database');

    // Clear existing data
    console.log('Clearing existing data...');
    await connection.query('SET FOREIGN_KEY_CHECKS = 0');
    await connection.query('TRUNCATE TABLE inventory_reopen_history');
    await connection.query('TRUNCATE TABLE inventory_uncatalogued_items');
    await connection.query('TRUNCATE TABLE inventory_pending_items');
    await connection.query('TRUNCATE TABLE inventory_found_items');
    await connection.query('TRUNCATE TABLE inventory_records');
    await connection.query('TRUNCATE TABLE custody_assets');
    await connection.query('TRUNCATE TABLE custody_logs');
    await connection.query('TRUNCATE TABLE maintenance_history');
    await connection.query('TRUNCATE TABLE asset_photos');
    await connection.query('TRUNCATE TABLE assets');
    await connection.query('TRUNCATE TABLE users');
    await connection.query('TRUNCATE TABLE sectors');
    await connection.query('SET FOREIGN_KEY_CHECKS = 1');
    console.log('✓ Data cleared');

    // Insert sectors
    console.log('Inserting sectors...');
    const sectorIds = [];
    for (let sector of sectors) {
      const id = uuidv4();
      sectorIds.push(id);
      await connection.query(
        'INSERT INTO sectors (id, name, description) VALUES (?, ?, ?)',
        [id, sector.name, sector.description]
      );
    }
    console.log(`✓ Inserted ${sectors.length} sectors`);

    // Insert users
    console.log('Inserting users...');
    const user_ids = [];
    for (let user of users) {
      const id = uuidv4();
      user_ids.push(id);
      await connection.query(
        'INSERT INTO users (id, name, `rank`, military_id, sector_id, is_active) VALUES (?, ?, ?, ?, ?, ?)',
        [id, user.name, user.rank, user.militaryId, sectorIds[user.sectorIndex], user.active ? 1 : 0]
      );
    }
    console.log(`✓ Inserted ${users.length} users`);

    // Insert assets
    console.log('Inserting assets...');
    const assetIds = [];
    for (let asset of assets) {
      const id = uuidv4();
      assetIds.push(id);
      const custodianId = asset.custodianIndex !== null ? user_ids[asset.custodianIndex] : null;
      await connection.query(
        `INSERT INTO assets (
          id, qr_code, name, category, serial_number, patrimony_id,
          sector_id, custodian_user_id, status, acquisition_date, warranty_expiry
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
        [
          id, asset.qrCode, asset.name, asset.category, asset.serialNumber,
          asset.patrimonyId || null, sectorIds[asset.sectorIndex], custodianId,
          asset.status, asset.acquisitionDate, asset.warrantyExpiry || null
        ]
      );
    }
    console.log(`✓ Inserted ${assets.length} assets`);

    // Insert custody logs
    console.log('Inserting custody logs...');
    const custodyIds = [];
    for (let custody of custodyLogs) {
      const id = uuidv4();
      custodyIds.push(id);
      await connection.query(
        'INSERT INTO custody_logs (id, cautela_number, user_id, checkout_date, checkin_date, notes) VALUES (?, ?, ?, ?, ?, ?)',
        [id, custody.cautela_number, user_ids[custody.userIndex], custody.checkout_date, custody.checkin_date, custody.notes]
      );

      // Insert custody assets relationships
      for (let assetIndex of custody.assetIndices) {
        const custodyAssetId = uuidv4();
        await connection.query(
          'INSERT INTO custody_assets (id, custody_log_id, asset_id) VALUES (?, ?, ?)',
          [custodyAssetId, id, assetIds[assetIndex]]
        );
      }
    }
    console.log(`✓ Inserted ${custodyLogs.length} custody logs with associated assets`);

    // Add maintenance history for asset SGAITI-0010
    console.log('Adding maintenance history...');
    const asset10Index = 9; // SGAITI-0010
    const maintenanceId = uuidv4();
    await connection.query(
      'INSERT INTO maintenance_history (id, asset_id, date, description, performed_by) VALUES (?, ?, ?, ?, ?)',
      [maintenanceId, assetIds[asset10Index], '2024-06-01', 'Substituição do kit de baterias. Problema: Baterias com baixa autonomia.', 'Técnico Externo']
    );
    console.log('✓ Added maintenance history');

    console.log('\n✓ Seed completed successfully!');
    console.log('\nSummary:');
    console.log(`- ${sectors.length} sectors created`);
    console.log(`- ${users.length} users created`);
    console.log(`- ${assets.length} assets created`);
    console.log(`- ${custodyLogs.length} custody logs created`);
    console.log(`- 1 maintenance record created`);

  } catch (error) {
    console.error('Error seeding database:', error);
    process.exit(1);
  } finally {
    if (connection) {
      await connection.end();
    }
  }
}

// Run seed
seed();
