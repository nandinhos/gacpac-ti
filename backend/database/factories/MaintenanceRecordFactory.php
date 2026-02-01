<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceRecordFactory extends Factory
{
    protected $model = \App\Models\MaintenanceRecord::class;

    public function definition(): array
    {
        return [
            "asset_id" => \App\Models\Asset::factory(),
            "date" => $this->faker->date(),
            "description" => $this->faker->sentence(),
            "performed_by" => $this->faker->name(),
            "cost" => $this->faker->randomFloat(2, 50, 500),
        ];
    }
}
