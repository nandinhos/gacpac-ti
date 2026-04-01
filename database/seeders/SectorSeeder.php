<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sectors = [
            ['name' => 'CHF', 'code' => 'CHF', 'description' => 'Chefia'],
            ['name' => 'ATI', 'code' => 'ATI', 'description' => 'Assessoria de Tecnologia da Informação'],
            ['name' => 'AIT', 'code' => 'AIT', 'description' => 'Assessoria de Inteligência'],
            ['name' => 'SEC', 'code' => 'SEC', 'description' => 'Secretaria'],
            ['name' => 'ALOG', 'code' => 'ALOG', 'description' => 'Assessoria Logística'],
            ['name' => 'SFI', 'code' => 'SFI', 'description' => 'Seção Financeira'],
            ['name' => 'SAD', 'code' => 'SAD', 'description' => 'Seção Administrativa'],
            ['name' => 'STEC', 'code' => 'STEC', 'description' => 'Seção Técnica'],
            ['name' => 'SCP-SIS', 'code' => 'SCP', 'description' => 'Seção de Coordenação de Projetos e Sistemas'],
            ['name' => 'Almoxarifado TI', 'code' => 'ALM-TI', 'description' => 'Depósito de material de TI'],
        ];

        foreach ($sectors as $sector) {
            \App\Models\Sector::create($sector);
        }
    }
}
