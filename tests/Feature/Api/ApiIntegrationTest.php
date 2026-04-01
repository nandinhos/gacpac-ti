<?php

namespace Tests\Feature\Api;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApiIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Roles and Permissions for Spatie
        $adminRole = Role::create(['name' => 'admin']);
        $viewerRole = Role::create(['name' => 'viewer']);

        Permission::create(['name' => 'assets.view']);
        Permission::create(['name' => 'assets.create']);
        Permission::create(['name' => 'users.manage']);

        $adminRole->givePermissionTo(['assets.view', 'assets.create', 'users.manage']);
        $viewerRole->givePermissionTo(['assets.view']);
    }

    public function test_unauthenticated_user_cannot_access_api()
    {
        $response = $this->getJson('/api/me');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_access_me_endpoint()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/me');

        $response->assertStatus(200)
            ->assertJsonPath('data.email', $user->email);
    }

    public function test_admin_can_list_assets()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $sector = Sector::factory()->create();
        $category = Category::factory()->create();
        Asset::factory()->count(3)->create([
            'sector_id' => $sector->id,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($admin, 'sanctum')->getJson('/api/assets');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_viewer_can_list_assets_but_cannot_create()
    {
        $viewer = User::factory()->create();
        $viewer->assignRole('viewer');

        $response = $this->actingAs($viewer, 'sanctum')->getJson('/api/assets');
        $response->assertStatus(200);

        $response = $this->actingAs($viewer, 'sanctum')->postJson('/api/assets', [
            'name' => 'Novo Ativo',
            'status' => 'disponivel',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_manage_users()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin, 'sanctum')->getJson('/api/users');
        $response->assertStatus(200);
    }

    public function test_viewer_cannot_manage_users()
    {
        $viewer = User::factory()->create();
        $viewer->assignRole('viewer');

        $response = $this->actingAs($viewer, 'sanctum')->getJson('/api/users');
        $response->assertStatus(403);
    }
}
