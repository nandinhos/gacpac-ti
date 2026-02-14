<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guards = ['web', 'sanctum'];
        $permissions = [
            'assets.view', 'assets.create', 'assets.edit', 'assets.delete',
            'inventory.view', 'inventory.create', 'inventory.approve',
            'maintenance.view', 'maintenance.create', 'maintenance.edit',
            'reports.view',
            'users.manage',
            'audit.view',
        ];

        foreach ($guards as $guard) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission, 'guard_name' => $guard]);
            }

            $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);
            $admin->syncPermissions(Permission::where('guard_name', $guard)->get());

            Role::firstOrCreate(['name' => 'operator', 'guard_name' => $guard])
                ->syncPermissions([
                    'assets.view', 'assets.create', 'assets.edit',
                    'inventory.view', 'inventory.create',
                    'maintenance.view', 'maintenance.create', 'maintenance.edit',
                    'reports.view',
                ]);

            Role::firstOrCreate(['name' => 'auditor', 'guard_name' => $guard])
                ->syncPermissions([
                    'assets.view', 'inventory.view', 'maintenance.view', 'reports.view', 'audit.view',
                ]);
            
            Role::firstOrCreate(['name' => 'viewer', 'guard_name' => $guard])
                ->syncPermissions(['assets.view']);
        }

        // Create Admin User
        $user = User::updateOrCreate(
            ['email' => 'admin@sgaiti.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('password'),
                'rank' => 'Cel',
                'force' => 'FAB',
                'organization' => 'GAC-PAC',
                'is_active' => true,
                'is_military' => true
            ]
        );
        $user->assignRole('admin');

        // Garante o seu usuário nandinhos@gmail.com como admin também
        $nando = User::updateOrCreate(
            ['email' => 'nandinhos@gmail.com'],
            [
                'name' => 'Nando Dev',
                'password' => bcrypt('password'),
                'rank' => 'Cel',
                'force' => 'FAB',
                'organization' => 'GAC-PAC',
                'is_active' => true,
                'is_military' => true
            ]
        );
        $nando->assignRole('admin');
    }
}
