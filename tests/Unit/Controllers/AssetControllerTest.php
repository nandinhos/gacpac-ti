<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\AssetController;
use App\Models\Asset;
use App\Models\Sector;
use App\Models\MilitaryUser;
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
        $user = MilitaryUser::factory()->create([
            'role' => 'admin',
            'is_active' => true
        ]);
        
        Sanctum::actingAs($user);
    }

    public function test_index_returns_paginated_assets()
    {
        $sector = Sector::factory()->create();
        Asset::factory()->count(15)->create(['sector_id' => $sector->id]);

        $response = $this->getJson('/api/assets');

        $response->assertStatus(200)
                ->assertJsonCount(15)
                ->assertJsonStructure([
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'qr_code',
                        'status',
                        'condition',
                        'sector'
                    ]
                ]);
    }

    public function test_store_creates_new_asset()
    {
        $sector = Sector::factory()->create();
        
        $assetData = [
            'name' => 'Notebook Dell',
            'description' => 'Notebook para uso administrativo',
            'qr_code' => 'QR001',
            'sector_id' => $sector->id,
            'status' => 'Disponível',
            'condition' => 'Bom',
            'acquisition_date' => '2024-01-15',
            'acquisition_value' => 2500.00,
            'brand' => 'Dell',
            'model' => 'Inspiron 15',
            'serial_number' => 'DL123456789',
            'type' => 'COMPUTADOR',
            'category' => 'COMPUTACAO'
        ];

        $response = $this->postJson('/api/assets', $assetData);

        $response->assertStatus(201)
                ->assertJsonFragment([
                    'name' => 'Notebook Dell',
                    'qr_code' => 'QR001'
                ]);

        $this->assertDatabaseHas('assets', [
            'name' => 'Notebook Dell',
            'qr_code' => 'QR001'
        ]);
    }

    public function test_show_returns_asset_with_relationships()
    {
        $sector = Sector::factory()->create();
        $asset = Asset::factory()->create(['sector_id' => $sector->id]);

        $response = $this->getJson("/api/assets/{$asset->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'id',
                    'name',
                    'description',
                    'qr_code',
                    'sector',
                    'photos',
                    'maintenance_records'
                ]);
    }

    public function test_update_modifies_existing_asset()
    {
        $sector = Sector::factory()->create();
        $asset = Asset::factory()->create(['sector_id' => $sector->id]);

        $updateData = [
            'name' => 'Notebook Dell Atualizado',
            'description' => 'Descrição atualizada',
            'status' => 'Em Manutenção'
        ];

        $response = $this->putJson("/api/assets/{$asset->id}", $updateData);

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'name' => 'Notebook Dell Atualizado',
                    'status' => 'Em Manutenção'
                ]);

        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'name' => 'Notebook Dell Atualizado',
            'status' => 'Em Manutenção'
        ]);
    }

    public function test_destroy_deletes_asset()
    {
        $sector = Sector::factory()->create();
        $asset = Asset::factory()->create(['sector_id' => $sector->id]);

        $response = $this->deleteJson("/api/assets/{$asset->id}");

        $response->assertStatus(200)
                ->assertJson(['message' => 'Ativo excluído com sucesso']);
        $this->assertSoftDeleted('assets', ['id' => $asset->id]);
    }

    public function test_get_by_qr_code_returns_asset()
    {
        $sector = Sector::factory()->create();
        $asset = Asset::factory()->create([
            'sector_id' => $sector->id,
            'qr_code' => 'QR123'
        ]);

        $response = $this->getJson('/api/assets/qr/QR123');

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'qr_code' => 'QR123'
                ]);
    }

    public function test_get_next_qr_code_returns_incremental_code()
    {
        Asset::factory()->create(['qr_code' => 'QR001']);
        Asset::factory()->create(['qr_code' => 'QR002']);

        $response = $this->getJson('/api/assets/utils/next-qr-code');

        $response->assertStatus(200)
                ->assertJson([
                    'next_qr_code' => 'QR003'
                ]);
    }
}