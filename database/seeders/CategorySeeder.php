<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Computação',
                'description' => 'Desktops, notebooks, servidores, tablets e demais equipamentos de processamento'
            ],
            [
                'name' => 'Periféricos',
                'description' => 'Monitores, teclados, mouses, webcams, headsets, scanners e impressoras'
            ],
            [
                'name' => 'Comunicações',
                'description' => 'Switches, roteadores, access points, modems e equipamentos de rede'
            ],
            [
                'name' => 'Energia',
                'description' => 'Nobreaks, estabilizadores, fontes e equipamentos de energia'
            ],
            [
                'name' => 'Armazenamento',
                'description' => 'HDs externos, SSDs, pendrives, NAS e sistemas de backup'
            ],
            [
                'name' => 'Audiovisual',
                'description' => 'Projetores, TVs, sistemas de som e equipamentos de apresentação'
            ],
            [
                'name' => 'Outros Ativos de TI',
                'description' => 'Demais equipamentos e materiais de tecnologia da informação'
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
