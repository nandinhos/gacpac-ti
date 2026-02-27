<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SectorSeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            AdminUserSeeder::class,
            AssetSeeder::class,
            CustodyLogSeeder::class,
            InventorySeeder::class,
            MaintenanceRecordSeeder::class,
            RolesAndPermissionsSeeder::class,
        ]);
    }
}
