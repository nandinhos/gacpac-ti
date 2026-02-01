<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MilitaryUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sectors = \App\Models\Sector::all();

        $users = [
            ['name' => 'Ricardo Goulart', 'rank' => 'Coronel Aviador', 'military_id' => '111.222.333-01', 'registration' => '111222', 'role' => 'Comandante', 'email' => 'ricardo@gac.pac.br', 'sector_id' => $sectors[0]->id, 'is_active' => true, 'password' => 'password'],
            ['name' => 'Beatriz Almeida', 'rank' => 'Major Especialista', 'military_id' => '222.333.444-02', 'registration' => '222333', 'role' => 'Chefe ATI', 'email' => 'beatriz@gac.pac.br', 'sector_id' => $sectors[1]->id, 'is_active' => true, 'password' => 'password'],
            ['name' => 'Lucas Martins', 'rank' => 'Capitão de Infantaria', 'military_id' => '333.444.555-03', 'registration' => '333444', 'role' => 'Adjunto AIT', 'email' => 'lucas@gac.pac.br', 'sector_id' => $sectors[2]->id, 'is_active' => true, 'password' => 'password'],
            ['name' => 'Juliana Costa', 'rank' => 'Primeiro-Tenente Intendente', 'military_id' => '444.555.666-04', 'registration' => '444555', 'role' => 'Auxiliar SFI', 'email' => 'juliana@gac.pac.br', 'sector_id' => $sectors[5]->id, 'is_active' => true, 'password' => 'password'],
            ['name' => 'Fernando Oliveira', 'rank' => 'Segundo-Sargento BCT', 'military_id' => '555.666.777-05', 'registration' => '555666', 'role' => 'Técnico ATI', 'email' => 'fernando@gac.pac.br', 'sector_id' => $sectors[1]->id, 'is_active' => true, 'password' => 'password'],
            ['name' => 'Patrícia Souza', 'rank' => 'Terceiro-Sargento SAD', 'military_id' => '666.777.888-06', 'registration' => '666777', 'role' => 'Secretária SAD', 'email' => 'patricia@gac.pac.br', 'sector_id' => $sectors[6]->id, 'is_active' => true, 'password' => 'password'],
            ['name' => 'Gustavo Pereira', 'rank' => 'Cabo', 'military_id' => '777.888.999-07', 'registration' => '777888', 'role' => 'Auxiliar ALOG', 'email' => 'gustavo@gac.pac.br', 'sector_id' => $sectors[4]->id, 'is_active' => true, 'password' => 'password'],
            ['name' => 'Carla Dias', 'rank' => 'Soldado-de-Primeira-Classe', 'military_id' => '888.999.000-08', 'registration' => '888999', 'role' => 'Auxiliar SEC', 'email' => 'carla@gac.pac.br', 'sector_id' => $sectors[3]->id, 'is_active' => false, 'password' => 'password'],
            ['name' => 'Marcos Lima', 'rank' => 'Suboficial BCO', 'military_id' => '999.000.111-09', 'registration' => '999000', 'role' => 'Chefe STEC', 'email' => 'marcos@gac.pac.br', 'sector_id' => $sectors[7]->id, 'is_active' => true, 'password' => 'password'],
            ['name' => 'Helena Santos', 'rank' => 'Primeiro-Tenente Analista de Sistemas', 'military_id' => '000.111.222-10', 'registration' => '000111', 'role' => 'Analista SCP', 'email' => 'helena@gac.pac.br', 'sector_id' => $sectors[8]->id, 'is_active' => true, 'password' => 'password'],
            ['name' => 'Sérgio Ramos', 'rank' => 'Cabo', 'military_id' => '123.123.123-11', 'registration' => '123123', 'role' => 'Auxiliar TI', 'email' => 'sergio@gac.pac.br', 'sector_id' => $sectors[9]->id, 'is_active' => true, 'password' => 'password'],
            ['name' => 'Rafael Andrade', 'rank' => 'Terceiro-Sargento BCT', 'military_id' => '234.234.234-12', 'registration' => '234234', 'role' => 'Técnico TI', 'email' => 'rafael@gac.pac.br', 'sector_id' => $sectors[1]->id, 'is_active' => true, 'password' => 'password'],
            ['name' => 'Mariana Campos', 'rank' => 'Cabo', 'military_id' => '345.345.345-13', 'registration' => '345345', 'role' => 'Auxiliar TI', 'email' => 'mariana@gac.pac.br', 'sector_id' => $sectors[1]->id, 'is_active' => true, 'password' => 'password'],
            ['name' => 'Vanessa Rocha', 'rank' => 'Primeiro-Tenente Estagiária', 'military_id' => '456.456.456-14', 'registration' => '456456', 'role' => 'Estagiária ATI', 'email' => 'vanessa@gac.pac.br', 'sector_id' => $sectors[1]->id, 'is_active' => true, 'password' => 'password']
        ];

        foreach ($users as $user) {
            \App\Models\MilitaryUser::create($user);
        }
    }
}
