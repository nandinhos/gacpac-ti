<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryRecord;
use App\Models\User;
use App\Models\Sector;
use App\Models\Asset;
use App\Models\InventoryAsset;
use App\Models\ReopenHistory;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $sectors = Sector::all();
        $assets = Asset::all();

        if ($users->isEmpty() || $sectors->isEmpty() || $assets->isEmpty()) {
            $this->command->info('Skipping InventorySeeder: Not enough users, sectors, or assets.');
            return;
        }

        // Inventory Record 1: Pending
        $inventory1 = InventoryRecord::create([
            'commission_number' => 'INV-001/2025',
            'responsible_user_id' => $users->random()->id,
            'sector_id' => $sectors->random()->id,
            'start_date' => now()->subDays(10),
            'status' => 'Em Andamento',
            'notes' => 'Inventário inicial do setor de TI.',
        ]);

        $inventory1Assets = $assets->random(rand(3, 7));
        foreach ($inventory1Assets as $asset) {
            InventoryAsset::create([
                'inventory_id' => $inventory1->id,
                'asset_id' => $asset->id,
                'observation' => 'Status: found',
            ]);
        }

        // Inventory Record 2: Completed
        $inventory2 = InventoryRecord::create([
            'commission_number' => 'INV-002/2025',
            'responsible_user_id' => $users->random()->id,
            'sector_id' => $sectors->random()->id,
            'start_date' => now()->subDays(20),
            'end_date' => now()->subDays(15),
            'status' => 'Concluído',
            'notes' => 'Inventário concluído do setor administrativo.',
        ]);

        $inventory2Assets = $assets->random(rand(5, 10));
        foreach ($inventory2Assets as $asset) {
            $assetStatus = collect(['found', 'missing', 'divergence'])->random();
            $notes = $assetStatus === 'missing' ? 'Não localizado no setor.' : null;
            InventoryAsset::create([
                'inventory_id' => $inventory2->id,
                'asset_id' => $asset->id,
                'observation' => "Status: {$assetStatus}. Notes: {$notes}",
            ]);
        }

        // Inventory Record 3: Reopened
        $inventory3 = InventoryRecord::create([
            'commission_number' => 'INV-003/2025',
            'responsible_user_id' => $users->random()->id,
            'sector_id' => $sectors->random()->id,
            'start_date' => now()->subDays(30),
            'end_date' => now()->subDays(25),
            'status' => 'Reaberto',
            'notes' => 'Inventário reaberto para verificação de divergências.',
        ]);

        ReopenHistory::create([
            'inventory_id' => $inventory3->id,
            'reopened_by_user_id' => $users->random()->id,
            'reopened_at' => now()->subDays(24),
            'justification' => 'Divergências encontradas na contagem.',
        ]);

        $inventory3Assets = $assets->random(rand(4, 8));
        foreach ($inventory3Assets as $asset) {
            $assetStatus = collect(['found', 'missing', 'divergence'])->random();
            $notes = $assetStatus === 'divergence' ? 'QR Code ilegível.' : null;
            InventoryAsset::create([
                'inventory_id' => $inventory3->id,
                'asset_id' => $asset->id,
                'observation' => "Status: {$assetStatus}. Notes: {$notes}",
            ]);
        }

        // Inventory Record 4: Pending with some assets in custody
        $inventory4 = InventoryRecord::create([
            'commission_number' => 'INV-004/2025',
            'responsible_user_id' => $users->random()->id,
            'sector_id' => $sectors->random()->id,
            'start_date' => now()->subDays(5),
            'status' => 'Em Andamento',
            'notes' => 'Inventário com ativos em cautela.',
        ]);

        $inventory4Assets = $assets->random(rand(3, 6));
        foreach ($inventory4Assets as $asset) {
            $status = 'found';
            $notes = null;
            if ($asset->status === 'Em Uso') {
                $notes = 'Ativo em cautela com militar.';
            }
            InventoryAsset::create([
                'inventory_id' => $inventory4->id,
                'asset_id' => $asset->id,
                'observation' => "Status: {$status}. Notes: {$notes}",
            ]);
        }
    }
}
