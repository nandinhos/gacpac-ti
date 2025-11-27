<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MilitaryUser;
use App\Models\Sector;
use App\Models\InventoryRecord;
use App\Models\CustodyLog;
use App\Models\Asset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AccessControlTestSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Criar setores se não existirem
            $sectors = $this->createSectors();

            // Criar usuários de teste
            $users = $this->createUsers($sectors);

            // Criar inventários
            $inventories = $this->createInventories($sectors, $users);

            // Criar ativos
            $assets = $this->createAssets($sectors);

            // Criar cautelas
            $this->createCustodies($users, $assets);

            $this->command->info('Dados de teste criados com sucesso!');
            $this->printCredentials();
        });
    }

    private function createSectors()
    {
        $sectorNames = [
            'Administração',
            'Operações',
            'Logística',
            'Tecnologia da Informação',
            'Recursos Humanos',
        ];

        $sectors = [];
        foreach ($sectorNames as $name) {
            $sectors[] = Sector::firstOrCreate(
                ['name' => $name],
                ['is_active' => true]
            );
        }

        return $sectors;
    }

    private function createUsers($sectors)
    {
        $users = [];

        // 1 Administrador
        $users['admin'] = MilitaryUser::updateOrCreate(
            ['military_id' => 'admin'],
            [
                'name' => 'Administrador do Sistema',
                'rank' => 'Ten Cel',
                'military_id' => 'admin',
                'sector_id' => $sectors[0]->id,
                'email' => 'admin@gac.pac.br',
                'password' => Hash::make('admin123'),
                'is_active' => true,
                'user_role' => 'admin',
                'commission_inventories' => null,
            ]
        );

        // 2 Usuários de Comissão
        $users['commission1'] = MilitaryUser::updateOrCreate(
            ['military_id' => 'comissao001'],
            [
                'name' => 'João Silva Santos',
                'rank' => 'Cap',
                'military_id' => 'comissao001',
                'sector_id' => $sectors[1]->id,
                'email' => 'joao.santos@gac.pac.br',
                'password' => Hash::make('comissao123'),
                'is_active' => true,
                'user_role' => 'commission',
                'commission_inventories' => [1, 2], // Vinculado aos inventários 1 e 2
            ]
        );

        $users['commission2'] = MilitaryUser::updateOrCreate(
            ['military_id' => 'comissao002'],
            [
                'name' => 'Ana Paula Ferreira',
                'rank' => 'Maj',
                'military_id' => 'comissao002',
                'sector_id' => $sectors[2]->id,
                'email' => 'ana.ferreira@gac.pac.br',
                'password' => Hash::make('comissao123'),
                'is_active' => true,
                'user_role' => 'commission',
                'commission_inventories' => [3, 4], // Vinculado aos inventários 3 e 4
            ]
        );

        // 5 Usuários Regulares
        $regularUsers = [
            ['name' => 'Maria Oliveira Costa', 'rank' => '1º Ten', 'id' => 'user001', 'sector' => 0],
            ['name' => 'Pedro Henrique Lima', 'rank' => '2º Ten', 'id' => 'user002', 'sector' => 1],
            ['name' => 'Juliana Alves Souza', 'rank' => 'Sgt', 'id' => 'user003', 'sector' => 2],
            ['name' => 'Carlos Eduardo Rocha', 'rank' => 'Cb', 'id' => 'user004', 'sector' => 3],
            ['name' => 'Fernanda Cristina Dias', 'rank' => 'Sd', 'id' => 'user005', 'sector' => 4],
        ];

        foreach ($regularUsers as $index => $userData) {
            $users["user{$index}"] = MilitaryUser::updateOrCreate(
                ['military_id' => $userData['id']],
                [
                    'name' => $userData['name'],
                    'rank' => $userData['rank'],
                    'military_id' => $userData['id'],
                    'sector_id' => $sectors[$userData['sector']]->id,
                    'email' => strtolower(str_replace(' ', '.', $userData['name'])) . '@gac.pac.br',
                    'password' => Hash::make('user123'),
                    'is_active' => true,
                    'user_role' => 'user',
                    'commission_inventories' => null,
                ]
            );
        }

        return $users;
    }

    private function createInventories($sectors, $users)
    {
        $inventories = [];

        // Inventários para comissão 1 (IDs 1 e 2)
        $inventories[] = InventoryRecord::firstOrCreate(
            ['commission_number' => 'INV-001/2024'],
            [
                'commission_number' => 'INV-001/2024',
                'start_date' => now()->subDays(30),
                'end_date' => null,
                'sector_id' => $sectors[0]->id,
                'responsible_user_id' => $users['commission1']->id,
                'status' => 'Em Andamento',
                'notes' => 'Inventário do setor de Administração',
            ]
        );

        $inventories[] = InventoryRecord::firstOrCreate(
            ['commission_number' => 'INV-002/2024'],
            [
                'commission_number' => 'INV-002/2024',
                'start_date' => now()->subDays(25),
                'end_date' => null,
                'sector_id' => $sectors[1]->id,
                'responsible_user_id' => $users['commission1']->id,
                'status' => 'Em Andamento',
                'notes' => 'Inventário do setor de Operações',
            ]
        );

        // Inventários para comissão 2 (IDs 3 e 4)
        $inventories[] = InventoryRecord::firstOrCreate(
            ['commission_number' => 'INV-003/2024'],
            [
                'commission_number' => 'INV-003/2024',
                'start_date' => now()->subDays(20),
                'end_date' => null,
                'sector_id' => $sectors[2]->id,
                'responsible_user_id' => $users['commission2']->id,
                'status' => 'Em Andamento',
                'notes' => 'Inventário do setor de Logística',
            ]
        );

        $inventories[] = InventoryRecord::firstOrCreate(
            ['commission_number' => 'INV-004/2024'],
            [
                'commission_number' => 'INV-004/2024',
                'start_date' => now()->subDays(15),
                'end_date' => null,
                'sector_id' => $sectors[3]->id,
                'responsible_user_id' => $users['commission2']->id,
                'status' => 'Em Andamento',
                'notes' => 'Inventário do setor de TI',
            ]
        );

        return $inventories;
    }

    private function createAssets($sectors)
    {
        $assetTypes = [
            ['name' => 'Notebook Dell Latitude', 'category' => 'Informática'],
            ['name' => 'Monitor LG 24"', 'category' => 'Informática'],
            ['name' => 'Impressora HP LaserJet', 'category' => 'Informática'],
            ['name' => 'Mesa de Escritório', 'category' => 'Mobiliário'],
            ['name' => 'Cadeira Ergonômica', 'category' => 'Mobiliário'],
        ];

        $assets = [];
        $assetNumber = 1;

        foreach ($sectors as $sector) {
            foreach ($assetTypes as $type) {
                for ($i = 1; $i <= 2; $i++) {
                    $qrCode = sprintf('QR%05d', $assetNumber);
                    $patrimonyId = sprintf('PAT%05d', $assetNumber);

                    $assets[] = Asset::firstOrCreate(
                        ['qr_code' => $qrCode],
                        [
                            'name' => $type['name'],
                            'qr_code' => $qrCode,
                            'patrimony_id' => $patrimonyId,
                            'serial_number' => 'SN' . str_pad($assetNumber, 8, '0', STR_PAD_LEFT),
                            'manufacturer' => 'Fabricante ' . chr(65 + ($assetNumber % 5)),
                            'model' => 'Modelo ' . $assetNumber,
                            'category' => $type['category'],
                            'sector_id' => $sector->id,
                            'status' => 'Disponível',
                            'acquisition_date' => now()->subYears(rand(1, 5)),
                        ]
                    );

                    $assetNumber++;
                }
            }
        }

        return $assets;
    }

    private function createCustodies($users, $assets)
    {
        $cautelaNumber = 1;

        // Criar cautelas para cada usuário regular
        foreach (['user0', 'user1', 'user2', 'user3', 'user4'] as $userKey) {
            if (!isset($users[$userKey])) continue;

            $user = $users[$userKey];
            
            // Cada usuário tem 2-3 cautelas
            $numCautelas = rand(2, 3);
            
            for ($i = 0; $i < $numCautelas; $i++) {
                $cautela = CustodyLog::firstOrCreate(
                    ['cautela_number' => sprintf('%03d/GAC-PAC/2024', $cautelaNumber)],
                    [
                        'cautela_number' => sprintf('%03d/GAC-PAC/2024', $cautelaNumber),
                        'user_id' => $user->id,
                        'checkout_date' => now()->subDays(rand(10, 60)),
                        'checkin_date' => null, // Cautela ativa
                        'notes' => "Cautela de teste para {$user->name}",
                    ]
                );

                // Vincular 2-4 ativos aleatórios disponíveis
                $availableAssets = Asset::where('status', 'Disponível')
                    ->inRandomOrder()
                    ->limit(rand(2, 4))
                    ->get();

                foreach ($availableAssets as $asset) {
                    $cautela->assets()->syncWithoutDetaching($asset->id);
                    $asset->update([
                        'status' => 'Em Uso',
                        'custodian_user_id' => $user->id
                    ]);
                }

                $cautelaNumber++;
            }
        }
    }

    private function printCredentials()
    {
        $this->command->info("\n=== Credenciais de Teste ===\n");
        
        $this->command->info("ADMINISTRADOR:");
        $this->command->info("  Military ID: admin");
        $this->command->info("  Senha: admin123");
        $this->command->info("  Acesso: Total ao sistema\n");

        $this->command->info("COMISSÃO 1 (Inventários 1 e 2):");
        $this->command->info("  Military ID: comissao001");
        $this->command->info("  Senha: comissao123");
        $this->command->info("  Acesso: Inventários de Administração e Operações\n");

        $this->command->info("COMISSÃO 2 (Inventários 3 e 4):");
        $this->command->info("  Military ID: comissao002");
        $this->command->info("  Senha: comissao123");
        $this->command->info("  Acesso: Inventários de Logística e TI\n");

        $this->command->info("USUÁRIOS REGULARES:");
        $this->command->info("  Military ID: user001, user002, user003, user004, user005");
        $this->command->info("  Senha: user123");
        $this->command->info("  Acesso: Apenas suas próprias cautelas\n");
    }
}
