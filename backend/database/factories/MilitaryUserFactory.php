<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\MilitaryUser;
use App\Models\Sector;

class MilitaryUserFactory extends Factory
{
    protected $model = MilitaryUser::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'rank' => $this->faker->randomElement(['Soldado', 'Cabo', 'Sargento', 'Tenente', 'Capitão', 'Major']),
            'military_id' => $this->faker->unique()->numerify('#######-#'),
            'sector_id' => Sector::factory(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'password' => 'password', // Default password for tests
            'is_active' => true,
            'user_role' => 'user',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }
}
