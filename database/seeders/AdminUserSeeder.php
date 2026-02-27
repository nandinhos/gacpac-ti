<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@gac.pac.br'],
            [
                'name' => 'Administrador do Sistema',
                'rank' => 'Ten Cel',
                'military_id' => 'admin',
                'email' => 'admin@gac.pac.br',
                'password' => Hash::make('admin123'),
                'is_military' => true,
                'force' => 'FAB',
                'organization' => 'GAC-PAC',
            ]
        );

        // Commission user
        User::updateOrCreate(
            ['email' => 'joao.santos@gac.pac.br'],
            [
                'name' => 'João Silva Santos',
                'rank' => 'Cap',
                'military_id' => 'comissao001',
                'email' => 'joao.santos@gac.pac.br',
                'password' => Hash::make('comissao123'),
                'is_military' => true,
                'force' => 'FAB',
                'organization' => 'GAC-PAC',
            ]
        );

        // Regular user
        User::updateOrCreate(
            ['email' => 'maria.costa@gac.pac.br'],
            [
                'name' => 'Maria Oliveira Costa',
                'rank' => '1º Ten',
                'military_id' => 'user001',
                'email' => 'maria.costa@gac.pac.br',
                'password' => Hash::make('user123'),
                'is_military' => true,
                'force' => 'FAB',
                'organization' => 'GAC-PAC',
            ]
        );

        echo "✅ Usuários de teste criados:\n";
        echo "Admin: email='admin@gac.pac.br', senha='admin123'\n";
        echo "Comissão: email='joao.santos@gac.pac.br', senha='comissao123'\n";
        echo "Usuário: email='maria.costa@gac.pac.br', senha='user123'\n";
    }
}
