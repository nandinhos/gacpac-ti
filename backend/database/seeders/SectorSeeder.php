<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sectors = [
            ['name' => 'CHF', 'description' => 'Chefia'],
            ['name' => 'ATI', 'description' => 'Assessoria de Tecnologia da Informação'],
            ['name' => 'AIT', 'description' => 'Assessoria de Inteligência'],
            ['name' => 'SEC', 'description' => 'Secretaria'],
            ['name' => 'ALOG', 'description' => 'Assessoria Logística'],
            ['name' => 'SFI', 'description' => 'Seção Financeira'],
            ['name' => 'SAD', 'description' => 'Seção Administrativa'],
            ['name' => 'STEC', 'description' => 'Seção Técnica'],
            ['name' => 'SCP-SIS', 'description' => 'Seção de Coordenação de Projetos e Sistemas'],
            ['name' => 'Almoxarifado TI', 'description' => 'Depósito de material de TI']
        ];

        foreach ($sectors as $sector) {
            \App\Models\Sector::create($sector);
        }
    }
}
