<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asset;
use App\Models\Sector;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sectors = Sector::all();

        foreach ($sectors as $sector) {
            Asset::factory()->count(5)->create(['sector_id' => $sector->id]);
        }
    }
}