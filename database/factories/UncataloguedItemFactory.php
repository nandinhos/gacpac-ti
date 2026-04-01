<?php

namespace Database\Factories;

use App\Models\InventoryRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UncataloguedItem>
 */
class UncataloguedItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'inventory_id' => InventoryRecord::factory(),
            'description' => $this->faker->sentence(),
            'location' => $this->faker->word(),
            'found_date' => now(),
        ];
    }
}
