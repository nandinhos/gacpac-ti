<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sector>
 */
class SectorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Seção de Tecnologia da Informação',
                'Seção de Comunicações',
                'Seção de Administração',
                'Seção de Logística',
                'Seção de Recursos Humanos',
                'Seção de Operações',
                'Seção de Inteligência',
                'Seção de Segurança',
                'Comando',
                'Estado Maior',
            ]),
            'description' => $this->faker->optional()->sentence(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
