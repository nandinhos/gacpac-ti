<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;
use Database\Seeders\RolesAndPermissionsSeeder;

class NewAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_admin_can_access_user_management()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->get(route('admin.users'))
            ->assertStatus(200);
    }

    public function test_operator_cannot_access_user_management()
    {
        $operator = User::factory()->create();
        $operator->assignRole('operator');

        $this->actingAs($operator)
            ->get(route('admin.users'))
            ->assertStatus(403);
    }

    public function test_admin_can_access_audit_logs()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->get(route('admin.audit-logs'))
            ->assertStatus(200);
    }

    public function test_operator_cannot_access_audit_logs()
    {
        $operator = User::factory()->create();
        $operator->assignRole('operator');

        $this->actingAs($operator)
            ->get(route('admin.audit-logs'))
            ->assertStatus(403);
    }

    public function test_operator_can_create_asset()
    {
        $operator = User::factory()->create();
        $operator->assignRole('operator');

        $this->assertTrue($operator->can('assets.create'));
    }

    public function test_webuser_cannot_create_asset()
    {
        $user = User::factory()->create();
        $this->assertFalse($user->can('assets.create'));
    }
}
