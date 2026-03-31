<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\AssetController;
use App\Models\Asset;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class AssetControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create and authenticate a user
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        $permissions = ['assets.view', 'assets.create', 'assets.edit', 'assets.delete'];
        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }
        $role->syncPermissions($permissions);

        $user = User::factory()->create([
            'is_active' => true
        ]);
        $user->assignRole($role);
        
        Sanctum::actingAs($user);
    }

    public function test_index_returns_paginated_assets()
    {
        $category = \App\Models\Category::factory()->create();
        $sector = Sector::factory()->create();
        Asset::factory()->count(15)->create(['sector_id' => $sector->id, 'category_id' => $category->id]);

        $response = $this->getJson('/api/assets');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'qr_code',
                            'status',
                            'category_id',
                            'sector_id'
                        ]
                    ]
                ]);
    }

    public function test_store_creates_new_asset()
    {
        $category = \App\Models\Category::factory()->create();
        $sector = Sector::factory()->create();
        
        $assetData = [
            'name' => 'Notebook Dell',
            'model' => 'Inspiron 15',
            'brand' => 'Dell',
            'qr_code' => 'QR001',
            'serial_number' => 'DL123456789',
            'category_id' => $category->id,
            'sector_id' => $sector->id,
            'status' => 'Disponível',
        ];

        $response = $this->postJson('/api/assets', $assetData);

        $response->assertStatus(201)
                ->assertJsonPath('data.name', 'Notebook Dell');

        $this->assertDatabaseHas('assets', [
            'name' => 'Notebook Dell',
            'serial_number' => 'DL123456789'
        ]);
    }

    public function test_show_returns_asset_with_relationships()
    {
        $category = \App\Models\Category::factory()->create();
        $sector = Sector::factory()->create();
        $asset = Asset::factory()->create(['sector_id' => $sector->id, 'category_id' => $category->id]);

        $response = $this->getJson("/api/assets/{$asset->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'name',
                        'qr_code',
                        'category',
                        'sector'
                    ]
                ]);
    }

    public function test_update_modifies_existing_asset()
    {
        $category = \App\Models\Category::factory()->create();
        $sector = Sector::factory()->create();
        $asset = Asset::factory()->create(['sector_id' => $sector->id, 'category_id' => $category->id]);

        $updateData = [
            'name' => 'Notebook Dell Atualizado',
            'status' => 'Em Manutenção'
        ];

        $response = $this->putJson("/api/assets/{$asset->id}", $updateData);

        $response->assertStatus(200)
                ->assertJsonPath('data.name', 'Notebook Dell Atualizado');

        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'name' => 'Notebook Dell Atualizado',
            'status' => 'Em Manutenção'
        ]);
    }

    public function test_destroy_deletes_asset()
    {
        $category = \App\Models\Category::factory()->create();
        $sector = Sector::factory()->create();
        $asset = Asset::factory()->create(['sector_id' => $sector->id, 'category_id' => $category->id]);

        $response = $this->deleteJson("/api/assets/{$asset->id}");

        $response->assertStatus(200)
                ->assertJson(['message' => 'Ativo removido com sucesso.']);
        
        $this->assertSoftDeleted('assets', ['id' => $asset->id]);
    }

    public function test_get_by_qr_code_returns_asset()
    {
        $category = \App\Models\Category::factory()->create();
        $sector = Sector::factory()->create();
        $asset = Asset::factory()->create([
            'sector_id' => $sector->id,
            'category_id' => $category->id,
            'qr_code' => 'QR123'
        ]);

        $response = $this->getJson('/api/assets/qr/QR123');

        $response->assertStatus(200)
                ->assertJsonPath('data.qr_code', 'QR123');
    }

    public function test_get_next_qr_code_returns_incremental_code()
    {
        $response = $this->getJson('/api/assets/utils/next-qr-code');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'qr_code'
                ]);
    }
}