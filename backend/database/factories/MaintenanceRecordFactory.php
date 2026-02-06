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
            "type" => $this->faker->randomElement(['preventiva', 'corretiva']),
            "date" => $this->faker->date(),
            "description" => $this->faker->sentence(),
            "performed_by" => $this->faker->name(),
            "cost" => $this->faker->randomFloat(2, 50, 500),
            "next_maintenance_date" => $this->faker->optional(0.5)->dateTimeBetween('now', '+6 months')?->format('Y-m-d'),
            "notes" => $this->faker->optional(0.3)->paragraph(),
        ];
    }
}
