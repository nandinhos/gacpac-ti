<?php

namespace Database\Factories;

use App\Models\InventoryRecord;
use App\Models\Sector;
use App\Models\MilitaryUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryRecord>
 */
class InventoryRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'commission_number' => $this->faker->unique()->bothify('INV-###/2025'),
            'start_date' => now(),
            'end_date' => null,
            'sector_id' => Sector::factory(),
            'responsible_user_id' => MilitaryUser::factory(),
            'status' => 'Em Andamento',
            'notes' => $this->faker->sentence(),
        ];
    }
}
