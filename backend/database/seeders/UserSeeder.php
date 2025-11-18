<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MilitaryUser;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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

        MilitaryUser::factory()->count(10)->create();
    }
}
