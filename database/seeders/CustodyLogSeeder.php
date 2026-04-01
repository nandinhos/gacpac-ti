<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CustodyLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::all();
        $assets = \App\Models\Asset::all();

        $custodyLogs = [
            [
                'cautela_number' => '001/GAC-PAC/2024',
                'user_id' => $users[1]->id,
                'checkout_date' => '2024-01-15',
                'checkin_date' => '2024-02-15',
                'notes' => 'Empréstimo para projeto de desenvolvimento',
                'asset_indices' => [1, 3, 13],
            ],
            [
                'cautela_number' => '002/GAC-PAC/2024',
                'user_id' => $users[2]->id,
                'checkout_date' => '2024-02-01',
                'checkin_date' => null,
                'notes' => 'Equipamentos para trabalho remoto',
                'asset_indices' => [0, 4, 14],
            ],
            [
                'cautela_number' => '003/GAC-PAC/2024',
                'user_id' => $users[4]->id,
                'checkout_date' => '2024-02-20',
                'checkin_date' => null,
                'notes' => 'Equipamentos para desenvolvimento de software',
                'asset_indices' => [7, 19],
            ],
            [
                'cautela_number' => '004/GAC-PAC/2024',
                'user_id' => $users[8]->id,
                'checkout_date' => '2024-03-01',
                'checkin_date' => '2024-03-15',
                'notes' => 'Equipamentos temporários para manutenção',
                'asset_indices' => [6, 23, 24, 25],
            ],
            [
                'cautela_number' => '005/GAC-PAC/2024',
                'user_id' => $users[9]->id,
                'checkout_date' => '2024-03-10',
                'checkin_date' => null,
                'notes' => 'Equipamentos para desenvolvimento mobile',
                'asset_indices' => [10, 32, 33],
            ],
            [
                'cautela_number' => '006/GAC-PAC/2024',
                'user_id' => $users[11]->id,
                'checkout_date' => '2024-03-20',
                'checkin_date' => null,
                'notes' => 'Equipamentos para trabalho administrativo',
                'asset_indices' => [27, 28, 29, 30],
            ],
            [
                'cautela_number' => '007/GAC-PAC/2024',
                'user_id' => $users[12]->id,
                'checkout_date' => '2024-04-01',
                'checkin_date' => null,
                'notes' => 'Equipamentos para projeto especial',
                'asset_indices' => [31, 34, 35],
            ],
            [
                'cautela_number' => '008/GAC-PAC/2024',
                'user_id' => $users[13]->id,
                'checkout_date' => '2024-04-10',
                'checkin_date' => null,
                'notes' => 'Equipamentos para estágio',
                'asset_indices' => [36, 37],
            ],
            [
                'cautela_number' => '009/GAC-PAC/2023',
                'user_id' => $users[0]->id,
                'checkout_date' => '2023-12-01',
                'checkin_date' => '2023-12-20',
                'notes' => 'Equipamentos para sala de servidores',
                'asset_indices' => [2, 5, 9],
            ],
            [
                'cautela_number' => '010/GAC-PAC/2023',
                'user_id' => $users[3]->id,
                'checkout_date' => '2023-11-15',
                'checkin_date' => '2023-12-01',
                'notes' => 'Equipamentos para apresentação',
                'asset_indices' => [8, 11],
            ],
            [
                'cautela_number' => '011/GAC-PAC/2024',
                'user_id' => $users[6]->id,
                'checkout_date' => '2024-01-05',
                'checkin_date' => null,
                'notes' => 'Equipamentos para laboratório',
                'asset_indices' => [15, 16],
            ],
        ];

        foreach ($custodyLogs as $custodyData) {
            $assetIndices = $custodyData['asset_indices'];
            unset($custodyData['asset_indices']);

            $custodyLog = \App\Models\CustodyLog::create($custodyData);

            foreach ($assetIndices as $index) {
                $custodyLog->assets()->attach($assets[$index]);
            }
        }
    }
}
