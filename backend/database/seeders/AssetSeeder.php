<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asset;
use App\Models\Sector;
use Faker\Generator as Faker;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $sectors = Sector::all();

        foreach ($sectors as $sector) {
            for ($i = 0; $i < 5; $i++) {
                Asset::create([
                    'name' => 'Asset ' . $i,
                    'qr_code' => $faker->unique()->ean8,
                    'serial_number' => $faker->unique()->ean13,
                    'patrimony_id' => $faker->unique()->numerify('######'),
                    'type' => 'COMPUTADOR',
                    'category' => 'COMPUTACAO',
                    'status' => 'DISPONIVEL',
                    'condition' => 'NOVO',
                    'sector_id' => $sector->id,
                    'custodian_user_id' => null,
                    'acquisition_date' => $faker->date,
                    'purchase_value' => $faker->randomFloat(2, 100, 5000),
                    'notes' => $faker->sentence,
                ]);
            }
        }
    }
}