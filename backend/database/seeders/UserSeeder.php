<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MilitaryUser;
use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        MilitaryUser::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'rank' => 'Coronel',
            'military_id' => '12345678',
            'is_active' => true,
            'user_role' => 'admin',
        ]);

        for ($i = 0; $i < 10; $i++) {
            MilitaryUser::create([
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => Hash::make('password'),
                'rank' => 'Soldado',
                'military_id' => $faker->unique()->numerify('########'),
                'is_active' => true,
                'user_role' => 'user',
            ]);
        }
    }
}
