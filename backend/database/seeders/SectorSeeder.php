<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sector;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sector::create(['name' => 'Comando', 'description' => 'Setor do Comando']);
        Sector::create(['name' => 'A1', 'description' => 'Setor de Pessoal']);
        Sector::create(['name' => 'A2', 'description' => 'Setor de Inteligência']);
        Sector::create(['name' => 'A3', 'description' => 'Setor de Operações']);
        Sector::create(['name' => 'A4', 'description' => 'Setor de Logística']);
        Sector::create(['name' => 'Almoxarifado', 'description' => 'Setor de Almoxarifado']);
    }
}