<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AssetPhotoFactory extends Factory
{
    protected $model = \App\Models\AssetPhoto::class;

    public function definition(): array
    {
        return [
            'asset_id' => \App\Models\Asset::factory(),
            'url' => 'asset-photos/'.$this->faker->uuid().'.jpg',
            'caption' => $this->faker->sentence(),
            'uploaded_at' => now(),
            'mime_type' => 'image/jpeg',
            'is_primary' => false,
            'file_size' => $this->faker->numberBetween(100000, 5000000),
        ];
    }
}
