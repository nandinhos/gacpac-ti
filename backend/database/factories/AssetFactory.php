<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Sector;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'brand' => $this->faker->randomElement(['Dell', 'HP', 'Lenovo', 'Acer', 'Samsung', 'LG']),
            'model' => $this->faker->bothify('??##??'),
            'serial_number' => $this->faker->unique()->bothify('???########'),
            'patrimony_number' => $this->faker->unique()->bothify('PAT####'),
            'type' => $this->faker->randomElement([
                'COMPUTADOR', 'NOTEBOOK', 'MONITOR', 'TECLADO', 'MOUSE', 
                'IMPRESSORA', 'SCANNER', 'ROTEADOR', 'SWITCH', 'SERVIDOR',
                'TELEFONE', 'CELULAR', 'TABLET', 'PROJETOR', 'CAMERA',
                'HD_EXTERNO', 'PENDRIVE', 'OUTROS'
            ]),
            'category' => $this->faker->randomElement([
                'COMPUTACAO', 'PERIFERICOS', 'REDE', 'COMUNICACAO', 
                'AUDIOVISUAL', 'ARMAZENAMENTO', 'OUTROS'
            ]),
            'status' => $this->faker->randomElement([
                'DISPONIVEL', 'EM_USO', 'MANUTENCAO', 'BAIXADO', 'EXTRAVIADO'
            ]),
            'condition' => $this->faker->randomElement([
                'NOVO', 'BOM', 'REGULAR', 'RUIM', 'INSERVIVEL'
            ]),
            'sector_id' => Sector::factory(),
            'acquisition_date' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'warranty_expiry' => $this->faker->optional()->dateTimeBetween('now', '+3 years')?->format('Y-m-d'),
            'purchase_value' => $this->faker->optional()->randomFloat(2, 100, 50000),
            'notes' => $this->faker->optional()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
