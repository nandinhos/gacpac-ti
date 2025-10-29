// services/mockData.ts

import { Asset, Sector, MilitaryUser, CustodyLog, AssetStatus, AssetCategory, InventoryRecord } from '../types';

// Setores da Força Aérea Brasileira (Exemplo)
export const initialSectors: Sector[] = [
  { id: 1, name: 'CHF', description: 'Chefia' },
  { id: 2, name: 'ATI', description: 'Assessoria de Tecnologia da Informação' },
  { id: 3, name: 'AIT', description: 'Assessoria de Inteligência' },
  { id: 4, name: 'SEC', description: 'Secretaria' },
  { id: 5, name: 'ALOG', description: 'Assessoria Logística' },
  { id: 6, name: 'SFI', description: 'Seção Financeira' },
  { id: 7, name: 'SAD', description: 'Seção Administrativa' },
  { id: 8, name: 'STEC', description: 'Seção Técnica' },
  { id: 9, name: 'SCP-SIS', description: 'Seção de Coordenação de Projetos e Sistemas' },
  { id: 10, name: 'Almoxarifado TI', description: 'Depósito de material de TI' }
];

// Militares da Força Aérea Brasileira (Exemplo)
export const initialUsers: MilitaryUser[] = [
  { id: 1, name: 'Ricardo Goulart', rank: 'Coronel Aviador', militaryId: '111.222.333-01', sectorId: 1, active: true },
  { id: 2, name: 'Beatriz Almeida', rank: 'Major Especialista', militaryId: '222.333.444-02', sectorId: 2, active: true },
  { id: 3, name: 'Lucas Martins', rank: 'Capitão de Infantaria', militaryId: '333.444.555-03', sectorId: 3, active: true },
  { id: 4, name: 'Juliana Costa', rank: 'Primeiro-Tenente Intendente', militaryId: '444.555.666-04', sectorId: 6, active: true },
  { id: 5, name: 'Fernando Oliveira', rank: 'Segundo-Sargento BCT', militaryId: '555.666.777-05', sectorId: 2, active: true },
  { id: 6, name: 'Patrícia Souza', rank: 'Terceiro-Sargento SAD', militaryId: '666.777.888-06', sectorId: 7, active: true },
  { id: 7, name: 'Gustavo Pereira', rank: 'Cabo', militaryId: '777.888.999-07', sectorId: 5, active: true },
  { id: 8, name: 'Carla Dias', rank: 'Soldado-de-Primeira-Classe', militaryId: '888.999.000-08', sectorId: 4, active: false },
  { id: 9, name: 'Marcos Lima', rank: 'Suboficial BCO', militaryId: '999.000.111-09', sectorId: 8, active: true },
  { id: 10, name: 'Helena Santos', rank: 'Primeiro-Tenente Analista de Sistemas', militaryId: '000.111.222-10', sectorId: 9, active: true },
  { id: 11, name: 'Sérgio Ramos', rank: 'Cabo', militaryId: '123.123.123-11', sectorId: 10, active: true },
  { id: 12, name: 'Rafael Andrade', rank: 'Terceiro-Sargento BCT', militaryId: '234.234.234-12', sectorId: 2, active: true },
  { id: 13, name: 'Mariana Campos', rank: 'Cabo', militaryId: '345.345.345-13', sectorId: 2, active: true },
  { id: 14, name: 'Vanessa Rocha', rank: 'Primeiro-Tenente Estagiária', militaryId: '456.456.456-14', sectorId: 2, active: true }
];

// Ativos de TI variados (Exemplo)
export const initialAssets: Asset[] = [
  // Ativos existentes...
  { id: 1, qrCode: 'SGAITI-0001', patrimonyId: 'FAB-1001', serialNumber: 'BR-A1B2C3D', category: AssetCategory.Computing, type: 'Desktop Dell Vostro 3888', photos: ['https://picsum.photos/id/10/200/200'], currentSectorId: 1, custodianUserId: 1, status: AssetStatus.InUse, acquisitionDate: '2023-02-10', warrantyEndDate: '2026-02-10' },
  { id: 2, qrCode: 'SGAITI-0002', patrimonyId: 'FAB-1002', serialNumber: 'US-E4F5G6H', category: AssetCategory.Computing, type: 'Notebook Dell Latitude 7420', photos: ['https://picsum.photos/id/20/200/200'], currentSectorId: 2, custodianUserId: 2, status: AssetStatus.InUse, acquisitionDate: '2023-05-15', warrantyEndDate: '2026-05-15' },
  { id: 3, qrCode: 'SGAITI-0003', patrimonyId: 'FAB-2001', serialNumber: 'SRV-I7J8K9L', category: AssetCategory.Computing, type: 'Servidor Dell PowerEdge R750', photos: [], currentSectorId: 2, status: AssetStatus.InUse, acquisitionDate: '2022-08-20' },
  { id: 4, qrCode: 'SGAITI-0004', serialNumber: 'MON-M1N2O3P', category: AssetCategory.Peripheral, type: 'Monitor Gamer LG UltraGear 27"', photos: ['https://picsum.photos/id/30/200/200'], currentSectorId: 3, custodianUserId: 3, status: AssetStatus.InUse, acquisitionDate: '2023-11-01' },
  { id: 5, qrCode: 'SGAITI-0005', patrimonyId: 'FAB-3001', serialNumber: 'PRT-Q4R5S6T', category: AssetCategory.Peripheral, type: 'Impressora Multifuncional Brother MFC-L8900CDW', photos: [], currentSectorId: 4, status: AssetStatus.InUse, acquisitionDate: '2021-09-05' },
  { id: 6, qrCode: 'SGAITI-0006', serialNumber: 'SWT-U7V8W9X', category: AssetCategory.Communications, type: 'Switch Cisco Catalyst 9200 24p', photos: [], currentSectorId: 2, status: AssetStatus.InUse, acquisitionDate: '2022-10-10' },
  { id: 7, qrCode: 'SGAITI-0007', patrimonyId: 'FAB-1003', serialNumber: 'BR-Y1Z2A3B', category: AssetCategory.Computing, type: 'Desktop HP EliteDesk 800 G9', photos: [], currentSectorId: 6, custodianUserId: 4, status: AssetStatus.InUse, acquisitionDate: '2023-03-12', warrantyEndDate: '2026-03-12' },
  { id: 8, qrCode: 'SGAITI-0008', patrimonyId: 'FAB-1004', serialNumber: 'CN-C4D5E6F', category: AssetCategory.Computing, type: 'Notebook Lenovo ThinkPad T14 Gen 3', photos: ['https://picsum.photos/id/40/200/200'], currentSectorId: 8, custodianUserId: 9, status: AssetStatus.InUse, acquisitionDate: '2023-07-20', warrantyEndDate: '2026-07-20' },
  { id: 9, qrCode: 'SGAITI-0009', serialNumber: 'RTR-G7H8I9J', category: AssetCategory.Communications, type: 'Roteador Wireless TP-Link Archer AX50', photos: [], currentSectorId: 5, custodianUserId: 7, status: AssetStatus.InUse, acquisitionDate: '2024-01-15' },
  { id: 10, qrCode: 'SGAITI-0010', patrimonyId: 'FAB-4001', serialNumber: 'UPS-K1L2M3N', category: AssetCategory.Power, type: 'Nobreak APC Smart-UPS 3000VA', photos: [], currentSectorId: 2, status: AssetStatus.Maintenance, acquisitionDate: '2020-06-30', maintenanceHistory: [{ id: 1, date: '2024-06-01', reportedProblem: 'Baterias com baixa autonomia', solution: 'Substituição do kit de baterias.' }] },
  { id: 11, qrCode: 'SGAITI-0011', patrimonyId: 'FAB-1005', serialNumber: 'TAB-O4P5Q6R', category: AssetCategory.Computing, type: 'Tablet Samsung Galaxy Tab S8', photos: ['https://picsum.photos/id/50/200/200'], currentSectorId: 9, custodianUserId: 10, status: AssetStatus.InUse, acquisitionDate: '2023-09-01', warrantyEndDate: '2024-09-01' },
  { id: 12, qrCode: 'SGAITI-0012', patrimonyId: 'FAB-5001', serialNumber: 'PRJ-S7T8U9V', category: AssetCategory.Other, type: 'Projetor Epson PowerLite E20', photos: [], currentSectorId: 7, custodianUserId: 6, status: AssetStatus.InUse, acquisitionDate: '2022-04-18' },
  { id: 13, qrCode: 'SGAITI-0013', serialNumber: 'SCN-W1X2Y3Z', category: AssetCategory.Peripheral, type: 'Scanner de Mesa Fujitsu ScanSnap iX1600', photos: [], currentSectorId: 10, status: AssetStatus.Available, acquisitionDate: '2023-10-05' },
  { id: 14, qrCode: 'SGAITI-0014', serialNumber: 'DCK-A2B3C4D', category: AssetCategory.Peripheral, type: 'Docking Station Dell WD19S', photos: [], currentSectorId: 2, custodianUserId: 2, status: AssetStatus.InUse, acquisitionDate: '2023-05-15' },
  { id: 15, qrCode: 'SGAITI-0015', serialNumber: 'CAM-E5F6G7H', category: AssetCategory.Peripheral, type: 'Webcam Logitech C920 Pro HD', photos: [], currentSectorId: 1, custodianUserId: 1, status: AssetStatus.InUse, acquisitionDate: '2023-02-10' },
  { id: 16, qrCode: 'SGAITI-0016', serialNumber: 'KBD-I8J9K1L', category: AssetCategory.Peripheral, type: 'Teclado Mecânico Redragon Kumara K552', photos: [], currentSectorId: 10, status: AssetStatus.Available, acquisitionDate: '2024-02-20' },
  { id: 17, qrCode: 'SGAITI-0017', serialNumber: 'MSE-M2N3O4P', category: AssetCategory.Peripheral, type: 'Mouse Logitech MX Master 3S', photos: [], currentSectorId: 10, status: AssetStatus.Available, acquisitionDate: '2024-02-20' },
  { id: 18, qrCode: 'SGAITI-0018', patrimonyId: 'FAB-2002', serialNumber: 'NAS-Q5R6S7T', category: AssetCategory.Computing, type: 'Servidor de Backup Synology DiskStation DS923+', photos: [], currentSectorId: 2, status: AssetStatus.Decommissioned, acquisitionDate: '2019-01-15' },
  
  // Novos ativos para o setor ATI (id: 2)
  // Estação de Trabalho da Maj Beatriz Almeida (id: 2) - complementando notebook e dock
  { id: 19, qrCode: 'SGAITI-0019', serialNumber: 'MON-DELL-U2421E', category: AssetCategory.Peripheral, type: 'Monitor Dell UltraSharp 24"', photos: [], currentSectorId: 2, custodianUserId: 2, status: AssetStatus.InUse, acquisitionDate: '2023-05-15' },
  { id: 20, qrCode: 'SGAITI-0020', serialNumber: 'KBD-DELL-KB216', category: AssetCategory.Peripheral, type: 'Teclado Dell KB216', photos: [], currentSectorId: 2, custodianUserId: 2, status: AssetStatus.InUse, acquisitionDate: '2023-05-15' },
  { id: 21, qrCode: 'SGAITI-0021', serialNumber: 'MSE-DELL-MS116', category: AssetCategory.Peripheral, type: 'Mouse Dell MS116', photos: [], currentSectorId: 2, custodianUserId: 2, status: AssetStatus.InUse, acquisitionDate: '2023-05-15' },

  // Estação de Trabalho do 2S Fernando Oliveira (id: 5)
  { id: 22, qrCode: 'SGAITI-0022', patrimonyId: 'FAB-1010', serialNumber: 'DT-DELL-OPT7090', category: AssetCategory.Computing, type: 'Desktop Dell Optiplex 7090', photos: [], currentSectorId: 2, custodianUserId: 5, status: AssetStatus.InUse, acquisitionDate: '2023-01-20' },
  { id: 23, qrCode: 'SGAITI-0023', serialNumber: 'MON-DELL-P2422H-1', category: AssetCategory.Peripheral, type: 'Monitor Dell 24"', photos: [], currentSectorId: 2, custodianUserId: 5, status: AssetStatus.InUse, acquisitionDate: '2023-01-20' },
  { id: 24, qrCode: 'SGAITI-0024', serialNumber: 'MON-DELL-P2422H-2', category: AssetCategory.Peripheral, type: 'Monitor Dell 24"', photos: [], currentSectorId: 2, custodianUserId: 5, status: AssetStatus.InUse, acquisitionDate: '2023-01-20' },
  { id: 25, qrCode: 'SGAITI-0025', serialNumber: 'KBD-DELL-KB216-2', category: AssetCategory.Peripheral, type: 'Teclado Dell KB216', photos: [], currentSectorId: 2, custodianUserId: 5, status: AssetStatus.InUse, acquisitionDate: '2023-01-20' },
  { id: 26, qrCode: 'SGAITI-0026', serialNumber: 'MSE-DELL-MS116-2', category: AssetCategory.Peripheral, type: 'Mouse Dell MS116', photos: [], currentSectorId: 2, custodianUserId: 5, status: AssetStatus.InUse, acquisitionDate: '2023-01-20' },
  { id: 27, qrCode: 'SGAITI-0027', serialNumber: 'HDS-LOGI-H390', category: AssetCategory.Peripheral, type: 'Headset Logitech H390', photos: [], currentSectorId: 2, custodianUserId: 5, status: AssetStatus.InUse, acquisitionDate: '2023-01-20' },
  
  // Estação de Trabalho do 3S Rafael Andrade (id: 12)
  { id: 28, qrCode: 'SGAITI-0028', patrimonyId: 'FAB-1011', serialNumber: 'DT-DELL-VOS3888', category: AssetCategory.Computing, type: 'Desktop Dell Vostro 3888', photos: [], currentSectorId: 2, custodianUserId: 12, status: AssetStatus.InUse, acquisitionDate: '2023-06-10' },
  { id: 29, qrCode: 'SGAITI-0029', serialNumber: 'MON-LG-22MP410', category: AssetCategory.Peripheral, type: 'Monitor LG 22"', photos: [], currentSectorId: 2, custodianUserId: 12, status: AssetStatus.InUse, acquisitionDate: '2023-06-10' },
  { id: 30, qrCode: 'SGAITI-0030', serialNumber: 'KBD-LOGI-K120', category: AssetCategory.Peripheral, type: 'Teclado Logitech K120', photos: [], currentSectorId: 2, custodianUserId: 12, status: AssetStatus.InUse, acquisitionDate: '2023-06-10' },
  { id: 31, qrCode: 'SGAITI-0031', serialNumber: 'MSE-LOGI-M90', category: AssetCategory.Peripheral, type: 'Mouse Logitech M90', photos: [], currentSectorId: 2, custodianUserId: 12, status: AssetStatus.InUse, acquisitionDate: '2023-06-10' },

  // Estação de Trabalho do CB Mariana Campos (id: 13)
  { id: 32, qrCode: 'SGAITI-0032', patrimonyId: 'FAB-1012', serialNumber: 'NB-DELL-LAT5420', category: AssetCategory.Computing, type: 'Notebook Dell Latitude 5420', photos: [], currentSectorId: 2, custodianUserId: 13, status: AssetStatus.InUse, acquisitionDate: '2023-08-01' },
  { id: 33, qrCode: 'SGAITI-0033', serialNumber: 'MON-DELL-P2422H-3', category: AssetCategory.Peripheral, type: 'Monitor Dell 24"', photos: [], currentSectorId: 2, custodianUserId: 13, status: AssetStatus.InUse, acquisitionDate: '2023-08-01' },
  { id: 34, qrCode: 'SGAITI-0034', serialNumber: 'DCK-DELL-WD19', category: AssetCategory.Peripheral, type: 'Docking Station Dell WD19', photos: [], currentSectorId: 2, custodianUserId: 13, status: AssetStatus.InUse, acquisitionDate: '2023-08-01' },
  { id: 35, qrCode: 'SGAITI-0035', serialNumber: 'KBD-DELL-KM5221W', category: AssetCategory.Peripheral, type: 'Teclado Externo Dell (sem fio)', photos: [], currentSectorId: 2, custodianUserId: 13, status: AssetStatus.InUse, acquisitionDate: '2023-08-01' },
  { id: 36, qrCode: 'SGAITI-0036', serialNumber: 'MSE-DELL-KM5221W', category: AssetCategory.Peripheral, type: 'Mouse Externo Dell (sem fio)', photos: [], currentSectorId: 2, custodianUserId: 13, status: AssetStatus.InUse, acquisitionDate: '2023-08-01' },
  
  // Estação de Trabalho da 1T Vanessa Rocha (id: 14)
  { id: 37, qrCode: 'SGAITI-0037', patrimonyId: 'FAB-1013', serialNumber: 'NB-LENOVO-T14', category: AssetCategory.Computing, type: 'Notebook Lenovo ThinkPad T14', photos: [], currentSectorId: 2, custodianUserId: 14, status: AssetStatus.InUse, acquisitionDate: '2024-02-15' },
  { id: 38, qrCode: 'SGAITI-0038', serialNumber: 'MSE-LOGI-M185', category: AssetCategory.Peripheral, type: 'Mouse sem fio Logitech M185', photos: [], currentSectorId: 2, custodianUserId: 14, status: AssetStatus.InUse, acquisitionDate: '2024-02-15' },
  
  // Ativos para Cautelas
  { id: 39, qrCode: 'SGAITI-0039', serialNumber: 'PRJ-BENQ-GV30', category: AssetCategory.Other, type: 'Projetor Portátil BenQ', photos: [], currentSectorId: 10, status: AssetStatus.Available, acquisitionDate: '2023-12-01' },
  { id: 40, qrCode: 'SGAITI-0040', serialNumber: 'CAM-CANON-T7I', category: AssetCategory.Other, type: 'Câmera DSLR Canon T7i com lente 18-55mm', photos: [], currentSectorId: 10, status: AssetStatus.Available, acquisitionDate: '2022-11-11' },

  // Ativos não atribuídos (compartilhados) no setor ATI
  { id: 41, qrCode: 'SGAITI-0041', patrimonyId: 'FAB-3005', serialNumber: 'PRT-HP-M428FDW', category: AssetCategory.Peripheral, type: 'Impressora de Rede HP LaserJet Pro M428fdw', photos: [], currentSectorId: 2, status: AssetStatus.InUse, acquisitionDate: '2022-09-20' },
  { id: 42, qrCode: 'SGAITI-0042', serialNumber: 'SCN-BROTHER-ADS2700W', category: AssetCategory.Peripheral, type: 'Scanner de Rede Brother ADS-2700W', photos: [], currentSectorId: 2, status: AssetStatus.InUse, acquisitionDate: '2023-03-30' },
];

export const initialCustodyLogs: CustodyLog[] = [
    { id: 1, cautela_number: '001/GAC-PAC/2023', user_id: 1, assetIds: [1, 15], checkout_date: '2023-02-15', term_url: '/terms/term-001.pdf' },
    { id: 2, cautela_number: '002/GAC-PAC/2023', user_id: 2, assetIds: [2, 14, 19, 20, 21], checkout_date: '2023-05-20', term_url: '/terms/term-002.pdf', signed_term_url: '/terms/signed-term-002.pdf' },
    { id: 3, cautela_number: '003/GAC-PAC/2023', user_id: 3, assetIds: [4], checkout_date: '2023-11-05', term_url: '/terms/term-003.pdf' },
    { id: 4, cautela_number: '004/GAC-PAC/2023', user_id: 4, assetIds: [7], checkout_date: '2023-03-18', term_url: '/terms/term-004.pdf' },
    { id: 5, cautela_number: '001/GAC-PAC/2022', user_id: 6, assetIds: [12], checkout_date: '2022-04-22', term_url: '/terms/term-005.pdf' },
    { id: 6, cautela_number: '005/GAC-PAC/2023', user_id: 9, assetIds: [8], checkout_date: '2023-07-25', term_url: '/terms/term-006.pdf', signed_term_url: '/terms/signed-term-006.pdf' },
    { id: 7, cautela_number: '006/GAC-PAC/2023', user_id: 10, assetIds: [11], checkout_date: '2023-09-02', term_url: '/terms/term-007.pdf' },
    { id: 8, cautela_number: '001/GAC-PAC/2024', user_id: 7, assetIds: [9], checkout_date: '2024-01-20', term_url: '/terms/term-008.pdf' },
    { id: 9, cautela_number: '007/GAC-PAC/2023', user_id: 8, assetIds: [], checkout_date: '2023-01-10', checkin_date: '2024-05-30', term_url: '/terms/term-009.pdf', signed_term_url: '/terms/signed-term-009.pdf' },
    // Novas Cautelas
    { id: 10, cautela_number: '002/GAC-PAC/2024', user_id: 5, assetIds: [39], checkout_date: '2024-07-10', term_url: '/terms/term-010.pdf' },
    { id: 11, cautela_number: '003/GAC-PAC/2024', user_id: 13, assetIds: [40], checkout_date: '2024-07-15', term_url: '/terms/term-011.pdf' }
];

const assetsToCount = initialAssets.filter(a => a.status !== AssetStatus.Decommissioned);

export const initialInventoryRecords: InventoryRecord[] = [
  {
    id: 1,
    startDate: '2024-07-01T10:00:00Z',
    completionDate: '2024-07-01T14:00:00Z',
    responsibleUserId: 2, // Maj Beatriz Almeida
    commissionNumber: 'CI-ATI-2024/01',
    summary: {
      total: assetsToCount.length,
      found: assetsToCount.length - 2,
      pending: 2,
      uncatalogued: 1,
    },
    foundAssets: assetsToCount.filter(a => ![16,17].includes(a.id)).map((asset) => ({
        ...asset,
        inventoryObservation: asset.id === 13 ? 'Scanner encontrado na caixa original, sem uso aparente.' : undefined,
    })),
    pendingAssets: assetsToCount.filter(a => a.id === 16 || a.id === 17).map(asset => ({...asset})),
    uncataloguedItems: ['QR-UNKNOWN-FAB-001'],
    status: 'Concluído',
    observations: 'Um dos mouses Logitech (SGAITI-0017) está com o scroll wheel defeituoso. A contagem foi realizada em todos os setores da unidade.',
  },
  {
    id: 2,
    startDate: '2024-07-10T09:00:00Z',
    responsibleUserId: 5, // Segundo-Sargento Fernando Oliveira
    sectorId: 1, // Inventário específico do setor CHF
    commissionNumber: 'CI-ATI-2024/02',
    summary: {
      total: assetsToCount.filter(a => a.currentSectorId === 1).length,
      found: 1,
      pending: assetsToCount.filter(a => a.currentSectorId === 1).length - 1,
      uncatalogued: 0,
    },
    foundAssets: assetsToCount.filter(a => a.id === 1).map(asset => ({...asset})),
    pendingAssets: assetsToCount.filter(a => a.currentSectorId === 1 && a.id !== 1).map(asset => ({...asset})),
    uncataloguedItems: [],
    status: 'Em Andamento',
    observations: 'Contagem parcial do setor da Chefia. Continuar amanhã.',
  },
];


export const generateNewQrCode = (lastId: number): string => {
    const newId = lastId + 1;
    return `SGAITI-${String(newId).padStart(4, '0')}`;
};
