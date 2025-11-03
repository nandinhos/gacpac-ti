<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MilitaryUser;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        MilitaryUser::updateOrCreate(
            ['military_id' => 'admin'],
            [
                'name' => 'Administrador do Sistema',
                'rank' => 'Ten Cel',
                'military_id' => 'admin',
                'email' => 'admin@gac.pac.br',
                'password' => Hash::make('admin123'),
                'is_active' => true,
                'user_role' => 'admin',
                'commission_inventories' => null,
            ]
        );

        // Commission user
        MilitaryUser::updateOrCreate(
            ['military_id' => 'comissao001'],
            [
                'name' => 'João Silva Santos',
                'rank' => 'Cap',
                'military_id' => 'comissao001',
                'email' => 'joao.santos@gac.pac.br',
                'password' => Hash::make('comissao123'),
                'is_active' => true,
                'user_role' => 'commission',
                'commission_inventories' => [1, 2], // IDs dos inventários que pode gerenciar
            ]
        );

        // Regular user
        MilitaryUser::updateOrCreate(
            ['military_id' => 'user001'],
            [
                'name' => 'Maria Oliveira Costa',
                'rank' => '1º Ten',
                'military_id' => 'user001',
                'email' => 'maria.costa@gac.pac.br',
                'password' => Hash::make('user123'),
                'is_active' => true,
                'user_role' => 'user',
                'commission_inventories' => null,
            ]
        );

        echo "✅ Usuários de teste criados:\n";
        echo "Admin: military_id='admin', senha='admin123'\n";
        echo "Comissão: military_id='comissao001', senha='comissao123'\n";
        echo "Usuário: military_id='user001', senha='user123'\n";
    }
}
