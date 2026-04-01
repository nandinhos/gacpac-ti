<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sectors = \App\Models\Sector::all();
        $users = \App\Models\User::all();

        $assets = [
            ['qr_code' => 'SGTI-0001', 'patrimony_id' => 'FAB-1001', 'serial_number' => 'BR-A1B2C3D', 'category' => 'Computação', 'name' => 'Desktop Dell Vostro 3888', 'sector_id' => $sectors[0]->id, 'custodian_user_id' => $users[0]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-02-10', 'warranty_expiry' => '2026-02-10'],
            ['qr_code' => 'SGTI-0002', 'patrimony_id' => 'FAB-1002', 'serial_number' => 'US-E4F5G6H', 'category' => 'Computação', 'name' => 'Notebook Dell Latitude 7420', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[1]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-05-15', 'warranty_expiry' => '2026-05-15'],
            ['qr_code' => 'SGTI-0003', 'patrimony_id' => 'FAB-2001', 'serial_number' => 'SRV-I7J8K9L', 'category' => 'Computação', 'name' => 'Servidor Dell PowerEdge R750', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => null, 'status' => 'Em Uso', 'acquisition_date' => '2022-08-20'],
            ['qr_code' => 'SGTI-0004', 'serial_number' => 'MON-M1N2O3P', 'category' => 'Periféricos', 'name' => 'Monitor Gamer LG UltraGear 27"', 'sector_id' => $sectors[2]->id, 'custodian_user_id' => $users[2]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-11-01'],
            ['qr_code' => 'SGTI-0005', 'patrimony_id' => 'FAB-3001', 'serial_number' => 'PRT-Q4R5S6T', 'category' => 'Periféricos', 'name' => 'Impressora Multifuncional Brother MFC-L8900CDW', 'sector_id' => $sectors[3]->id, 'custodian_user_id' => null, 'status' => 'Em Uso', 'acquisition_date' => '2021-09-05'],
            ['qr_code' => 'SGTI-0006', 'serial_number' => 'SWT-U7V8W9X', 'category' => 'Comunicações', 'name' => 'Switch Cisco Catalyst 9200 24p', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => null, 'status' => 'Em Uso', 'acquisition_date' => '2022-10-10'],
            ['qr_code' => 'SGTI-0007', 'patrimony_id' => 'FAB-1003', 'serial_number' => 'BR-Y1Z2A3B', 'category' => 'Computação', 'name' => 'Desktop HP EliteDesk 800 G9', 'sector_id' => $sectors[5]->id, 'custodian_user_id' => $users[3]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-03-12', 'warranty_expiry' => '2026-03-12'],
            ['qr_code' => 'SGTI-0008', 'patrimony_id' => 'FAB-1004', 'serial_number' => 'CN-C4D5E6F', 'category' => 'Computação', 'name' => 'Notebook Lenovo ThinkPad T14 Gen 3', 'sector_id' => $sectors[7]->id, 'custodian_user_id' => $users[8]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-07-20', 'warranty_expiry' => '2026-07-20'],
            ['qr_code' => 'SGTI-0009', 'serial_number' => 'RTR-G7H8I9J', 'category' => 'Comunicações', 'name' => 'Roteador Wireless TP-Link Archer AX50', 'sector_id' => $sectors[4]->id, 'custodian_user_id' => $users[6]->id, 'status' => 'Em Uso', 'acquisition_date' => '2024-01-15'],
            ['qr_code' => 'SGTI-0010', 'patrimony_id' => 'FAB-4001', 'serial_number' => 'UPS-K1L2M3N', 'category' => 'Energia', 'name' => 'Nobreak APC Smart-UPS 3000VA', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => null, 'status' => 'Manutenção', 'acquisition_date' => '2020-06-30'],
            ['qr_code' => 'SGTI-0011', 'patrimony_id' => 'FAB-1005', 'serial_number' => 'TAB-O4P5Q6R', 'category' => 'Computação', 'name' => 'Tablet Samsung Galaxy Tab S8', 'sector_id' => $sectors[8]->id, 'custodian_user_id' => $users[9]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-09-01', 'warranty_expiry' => '2024-09-01'],
            ['qr_code' => 'SGTI-0012', 'patrimony_id' => 'FAB-5001', 'serial_number' => 'PRJ-S7T8U9V', 'category' => 'Outros Ativos de TI', 'name' => 'Projetor Epson PowerLite E20', 'sector_id' => $sectors[6]->id, 'custodian_user_id' => $users[5]->id, 'status' => 'Em Uso', 'acquisition_date' => '2022-04-18'],
            ['qr_code' => 'SGTI-0013', 'serial_number' => 'SCN-W1X2Y3Z', 'category' => 'Periféricos', 'name' => 'Scanner de Mesa Fujitsu ScanSnap iX1600', 'sector_id' => $sectors[9]->id, 'custodian_user_id' => null, 'status' => 'Disponível', 'acquisition_date' => '2023-10-05'],
            ['qr_code' => 'SGTI-0014', 'serial_number' => 'DCK-A2B3C4D', 'category' => 'Periféricos', 'name' => 'Docking Station Dell WD19S', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[1]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-05-15'],
            ['qr_code' => 'SGTI-0015', 'serial_number' => 'CAM-E5F6G7H', 'category' => 'Periféricos', 'name' => 'Webcam Logitech C920 Pro HD', 'sector_id' => $sectors[0]->id, 'custodian_user_id' => $users[0]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-02-10'],
            ['qr_code' => 'SGTI-0016', 'serial_number' => 'KBD-I8J9K1L', 'category' => 'Periféricos', 'name' => 'Teclado Mecânico Redragon Kumara K552', 'sector_id' => $sectors[9]->id, 'custodian_user_id' => null, 'status' => 'Disponível', 'acquisition_date' => '2024-02-20'],
            ['qr_code' => 'SGTI-0017', 'serial_number' => 'MSE-M2N3O4P', 'category' => 'Periféricos', 'name' => 'Mouse Logitech MX Master 3S', 'sector_id' => $sectors[9]->id, 'custodian_user_id' => null, 'status' => 'Disponível', 'acquisition_date' => '2024-02-20'],
            ['qr_code' => 'SGTI-0018', 'patrimony_id' => 'FAB-2002', 'serial_number' => 'NAS-Q5R6S7T', 'category' => 'Computação', 'name' => 'Servidor de Backup Synology DiskStation DS923+', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => null, 'status' => 'Baixado', 'acquisition_date' => '2019-01-15'],
            ['qr_code' => 'SGTI-0019', 'serial_number' => 'MON-DELL-U2421E', 'category' => 'Periféricos', 'name' => 'Monitor Dell UltraSharp 24"', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[1]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-05-15'],
            ['qr_code' => 'SGTI-0020', 'serial_number' => 'KBD-DELL-KB216', 'category' => 'Periféricos', 'name' => 'Teclado Dell KB216', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[1]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-05-15'],
            ['qr_code' => 'SGTI-0021', 'serial_number' => 'MSE-DELL-MS116', 'category' => 'Periféricos', 'name' => 'Mouse Dell MS116', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[1]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-05-15'],
            ['qr_code' => 'SGTI-0022', 'patrimony_id' => 'FAB-1010', 'serial_number' => 'DT-DELL-OPT7090', 'category' => 'Computação', 'name' => 'Desktop Dell Optiplex 7090', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[4]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-01-20'],
            ['qr_code' => 'SGTI-0023', 'serial_number' => 'MON-DELL-P2422H-1', 'category' => 'Periféricos', 'name' => 'Monitor Dell 24"', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[4]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-01-20'],
            ['qr_code' => 'SGTI-0024', 'serial_number' => 'MON-DELL-P2422H-2', 'category' => 'Periféricos', 'name' => 'Monitor Dell 24"', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[4]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-01-20'],
            ['qr_code' => 'SGTI-0025', 'serial_number' => 'KBD-DELL-KB216-2', 'category' => 'Periféricos', 'name' => 'Teclado Dell KB216', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[4]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-01-20'],
            ['qr_code' => 'SGTI-0026', 'serial_number' => 'MSE-DELL-MS116-2', 'category' => 'Periféricos', 'name' => 'Mouse Dell MS116', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[4]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-01-20'],
            ['qr_code' => 'SGTI-0027', 'serial_number' => 'HDS-LOGI-H390', 'category' => 'Periféricos', 'name' => 'Headset Logitech H390', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[4]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-01-20'],
            ['qr_code' => 'SGTI-0028', 'patrimony_id' => 'FAB-1011', 'serial_number' => 'DT-DELL-VOS3888', 'category' => 'Computação', 'name' => 'Desktop Dell Vostro 3888', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[11]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-06-10'],
            ['qr_code' => 'SGTI-0029', 'serial_number' => 'MON-LG-22MP410', 'category' => 'Periféricos', 'name' => 'Monitor LG 22"', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[11]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-06-10'],
            ['qr_code' => 'SGTI-0030', 'serial_number' => 'KBD-LOGI-K120', 'category' => 'Periféricos', 'name' => 'Teclado Logitech K120', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[11]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-06-10'],
            ['qr_code' => 'SGTI-0031', 'serial_number' => 'MSE-LOGI-M90', 'category' => 'Periféricos', 'name' => 'Mouse Logitech M90', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[11]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-06-10'],
            ['qr_code' => 'SGTI-0032', 'patrimony_id' => 'FAB-1012', 'serial_number' => 'NB-DELL-LAT5420', 'category' => 'Computação', 'name' => 'Notebook Dell Latitude 5420', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[12]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-08-01'],
            ['qr_code' => 'SGTI-0033', 'serial_number' => 'MON-DELL-P2422H-3', 'category' => 'Periféricos', 'name' => 'Monitor Dell 24"', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[12]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-08-01'],
            ['qr_code' => 'SGTI-0034', 'serial_number' => 'DCK-DELL-WD19', 'category' => 'Periféricos', 'name' => 'Docking Station Dell WD19', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[12]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-08-01'],
            ['qr_code' => 'SGTI-0035', 'serial_number' => 'KBD-DELL-KM5221W', 'category' => 'Periféricos', 'name' => 'Teclado Externo Dell (sem fio)', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[12]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-08-01'],
            ['qr_code' => 'SGTI-0036', 'serial_number' => 'MSE-DELL-KM5221W', 'category' => 'Periféricos', 'name' => 'Mouse Externo Dell (sem fio)', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[12]->id, 'status' => 'Em Uso', 'acquisition_date' => '2023-08-01'],
            ['qr_code' => 'SGTI-0037', 'patrimony_id' => 'FAB-1013', 'serial_number' => 'NB-LENOVO-T14', 'category' => 'Computação', 'name' => 'Notebook Lenovo ThinkPad T14', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[13]->id, 'status' => 'Em Uso', 'acquisition_date' => '2024-02-15'],
            ['qr_code' => 'SGTI-0038', 'serial_number' => 'MSE-LOGI-M185', 'category' => 'Periféricos', 'name' => 'Mouse sem fio Logitech M185', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => $users[13]->id, 'status' => 'Em Uso', 'acquisition_date' => '2024-02-15'],
            ['qr_code' => 'SGTI-0039', 'serial_number' => 'PRJ-BENQ-GV30', 'category' => 'Outros Ativos de TI', 'name' => 'Projetor Portátil BenQ', 'sector_id' => $sectors[9]->id, 'custodian_user_id' => null, 'status' => 'Disponível', 'acquisition_date' => '2023-12-01'],
            ['qr_code' => 'SGTI-0040', 'serial_number' => 'CAM-CANON-T7I', 'category' => 'Outros Ativos de TI', 'name' => 'Câmera DSLR Canon T7i com lente 18-55mm', 'sector_id' => $sectors[9]->id, 'custodian_user_id' => null, 'status' => 'Disponível', 'acquisition_date' => '2022-11-11'],
            ['qr_code' => 'SGTI-0041', 'patrimony_id' => 'FAB-3005', 'serial_number' => 'PRT-HP-M428FDW', 'category' => 'Periféricos', 'name' => 'Impressora de Rede HP LaserJet Pro M428fdw', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => null, 'status' => 'Em Uso', 'acquisition_date' => '2022-09-20'],
            ['qr_code' => 'SGTI-0042', 'serial_number' => 'SCN-BROTHER-ADS2700W', 'category' => 'Periféricos', 'name' => 'Scanner de Rede Brother ADS-2700W', 'sector_id' => $sectors[1]->id, 'custodian_user_id' => null, 'status' => 'Em Uso', 'acquisition_date' => '2023-03-30'],
        ];

        foreach ($assets as $asset) {
            \App\Models\Asset::create($asset);
        }
    }
}
