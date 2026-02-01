<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Asset;
use App\Models\Sector;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class AssetControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_example(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_can_list_assets()
    {
        // Arrange
        $sector = Sector::factory()->create();
        Asset::factory()->count(3)->create(['sector_id' => $sector->id]);

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Act
        $response = $this->getJson('/api/assets');

        // Assert
        $response->assertStatus(200)
                ->assertJsonCount(3);
    }

    public function test_can_create_asset_with_valid_data()
    {
        // Arrange
        $sector = Sector::factory()->create();
        $assetData = [
            'brand' => 'Dell',
            'model' => 'Optiplex 3090',
            'type' => 'COMPUTADOR',
            'category' => 'COMPUTACAO',
            'status' => 'DISPONIVEL',
            'condition' => 'NOVO',
            'sector_id' => $sector->id,
            'serial_number' => 'DELL123456',
            'patrimony_number' => 'PAT001',
            'acquisition_date' => '2024-01-15',
            'purchase_value' => 2500.00,
            'notes' => 'Computador para uso administrativo'
        ];

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Act
        $response = $this->postJson('/api/assets', $assetData);

        // Assert
        $response->assertStatus(201)
                ->assertJson([
                    'message' => 'Ativo criado com sucesso'
                ])
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'brand',
                        'model',
                        'type',
                        'category',
                        'status'
                    ]
                ]);

        $this->assertDatabaseHas('assets', [
            'brand' => 'Dell',
            'model' => 'Optiplex 3090',
            'serial_number' => 'DELL123456'
        ]);
    }

    public function test_cannot_create_asset_with_invalid_data()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Act
        $response = $this->postJson('/api/assets', [
            'brand' => '', // required field empty
            'type' => 'INVALID_TYPE', // invalid enum value
            'category' => 'INVALID_CATEGORY', // invalid enum value
        ]);

        // Assert
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['brand', 'status', 'condition', 'sector_id']);
    }

    public function test_cannot_create_asset_with_duplicate_serial_number()
    {
        // Arrange
        $sector = Sector::factory()->create();
        Asset::factory()->create([
            'serial_number' => 'DUPLICATE123',
            'sector_id' => $sector->id
        ]);

        $assetData = [
            'brand' => 'HP',
            'model' => 'EliteBook',
            'type' => 'NOTEBOOK',
            'category' => 'COMPUTACAO',
            'status' => 'DISPONIVEL',
            'condition' => 'NOVO',
            'sector_id' => $sector->id,
            'serial_number' => 'DUPLICATE123', // duplicate
        ];

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Act
        $response = $this->postJson('/api/assets', [
            'brand' => 'HP',
            'model' => 'EliteBook',
            'type' => 'NOTEBOOK',
            'category' => 'COMPUTACAO',
            'status' => 'DISPONIVEL',
            'condition' => 'NOVO',
            'sector_id' => $sector->id,
            'serial_number' => 'DUPLICATE123', // duplicate
        ]);

        // Assert
        $response->assertStatus(422);
    }

    public function test_can_update_asset()
    {
        // Arrange
        $sector = Sector::factory()->create();
        $asset = Asset::factory()->create(['sector_id' => $sector->id]);

        $updateData = [
            'brand' => 'Updated Brand',
            'status' => 'MANUTENCAO'
        ];

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Act
        $response = $this->putJson("/api/assets/{$asset->id}", $updateData);

        // Assert
        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Ativo atualizado com sucesso'
                ]);

        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'brand' => 'Updated Brand',
            'status' => 'MANUTENCAO'
        ]);
    }

    public function test_can_delete_asset()
    {
        // Arrange
        $sector = Sector::factory()->create();
        $asset = Asset::factory()->create(['sector_id' => $sector->id]);

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Act
        $response = $this->deleteJson("/api/assets/{$asset->id}");

        // Assert
        $response->assertStatus(200)
                ->assertJson(['message' => 'Ativo excluído com sucesso']);

        // Assert
        $this->assertSoftDeleted('assets', ['id' => $asset->id]);
    }

    public function test_can_filter_assets_by_category()
    {
        // Arrange
        $sector = Sector::factory()->create();
        Asset::factory()->create(['category' => 'COMPUTACAO', 'sector_id' => $sector->id]);
        Asset::factory()->create(['category' => 'REDE', 'sector_id' => $sector->id]);
        Asset::factory()->create(['category' => 'COMPUTACAO', 'sector_id' => $sector->id]);

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Act
        $response = $this->getJson('/api/assets?category=COMPUTACAO');

        // Assert
        $response->assertStatus(200)
                ->assertJsonCount(2);
    }

    public function test_can_search_assets()
    {
        // Arrange
        $sector = Sector::factory()->create();
        Asset::factory()->create([
            'brand' => 'Dell',
            'model' => 'Optiplex 3090',
            'sector_id' => $sector->id
        ]);
        Asset::factory()->create([
            'brand' => 'Apple',
            'model' => 'MacBook Pro',
            'sector_id' => $sector->id
        ]);

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Act
        $response = $this->getJson('/api/assets?search=Dell');

        // Assert
        $response->assertStatus(200)
                ->assertJsonCount(1);
    }
}
