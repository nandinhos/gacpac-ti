<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\MaintenanceRecord;
use Illuminate\Database\Seeder;

class MaintenanceRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assets = Asset::all();

        if ($assets->isEmpty()) {
            return;
        }

        foreach ($assets->random(min($assets->count(), 10)) as $asset) {
            // Manutenções passadas (Concluídas)
            MaintenanceRecord::factory()->count(2)->create([
                'asset_id' => $asset->id,
                'date' => now()->subMonths(rand(1, 6)),
                'next_maintenance_date' => now()->subMonths(rand(0, 1)),
            ]);

            // Manutenções futuras (Próximas)
            MaintenanceRecord::factory()->create([
                'asset_id' => $asset->id,
                'date' => now()->subDays(rand(1, 5)),
                'next_maintenance_date' => now()->addDays(rand(5, 20)),
            ]);

            // Manutenções atrasadas (Overdue)
            MaintenanceRecord::factory()->create([
                'asset_id' => $asset->id,
                'date' => now()->subMonths(3),
                'next_maintenance_date' => now()->subDays(rand(5, 15)),
            ]);
        }
    }
}
