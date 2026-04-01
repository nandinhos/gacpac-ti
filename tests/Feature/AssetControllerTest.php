<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AssetControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed permissions
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        $permissions = ['assets.view', 'assets.create', 'assets.edit', 'assets.delete'];
        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }
        $role->syncPermissions($permissions);
    }

    private function createAdminUser()
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('admin');

        return $user;
    }

    public function test_can_list_assets()
    {
        // Arrange
        $category = Category::factory()->create();
        $sector = Sector::factory()->create();
        Asset::factory()->count(3)->create(['sector_id' => $sector->id, 'category_id' => $category->id]);

        $user = $this->createAdminUser();
        Sanctum::actingAs($user);

        // Act
        $response = $this->getJson('/api/assets');

        // Assert
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_asset_with_valid_data()
    {
        // Arrange
        $category = Category::factory()->create();
        $sector = Sector::factory()->create();
        $assetData = [
            'qr_code' => 'QR001',
            'name' => 'Dell Optiplex 3090',
            'brand' => 'Dell',
            'model' => 'Optiplex 3090',
            'category_id' => $category->id,
            'sector_id' => $sector->id,
            'status' => 'DISPONIVEL',
            'serial_number' => 'DELL123456',
        ];

        $user = $this->createAdminUser();
        Sanctum::actingAs($user);

        // Act
        $response = $this->postJson('/api/assets', $assetData);

        // Assert
        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Dell Optiplex 3090'); // No Resource Name é formatado? Verificarei.

        $this->assertDatabaseHas('assets', [
            'qr_code' => 'QR001',
            'serial_number' => 'DELL123456',
        ]);
    }

    public function test_cannot_create_asset_with_invalid_data()
    {
        $user = $this->createAdminUser();
        Sanctum::actingAs($user);

        // Act
        $response = $this->postJson('/api/assets', [
            'name' => '', // required
        ]);

        // Assert
        $response->assertStatus(422);
    }

    public function test_can_update_asset()
    {
        // Arrange
        $category = Category::factory()->create();
        $sector = Sector::factory()->create();
        $asset = Asset::factory()->create(['sector_id' => $sector->id, 'category_id' => $category->id]);

        $updateData = [
            'name' => 'Updated Name',
            'status' => 'MANUTENCAO',
        ];

        $user = $this->createAdminUser();
        Sanctum::actingAs($user);

        // Act
        $response = $this->putJson("/api/assets/{$asset->id}", $updateData);

        // Assert
        $response->assertStatus(200);

        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'name' => 'Updated Name',
            'status' => 'MANUTENCAO',
        ]);
    }

    public function test_can_delete_asset()
    {
        // Arrange
        $category = Category::factory()->create();
        $sector = Sector::factory()->create();
        $asset = Asset::factory()->create(['sector_id' => $sector->id, 'category_id' => $category->id]);

        $user = $this->createAdminUser();
        Sanctum::actingAs($user);

        // Act
        $response = $this->deleteJson("/api/assets/{$asset->id}");

        // Assert
        $response->assertStatus(200);
        $this->assertSoftDeleted('assets', ['id' => $asset->id]);
    }

    public function test_can_filter_assets_by_category()
    {
        // Arrange
        $cat1 = Category::factory()->create();
        $cat2 = Category::factory()->create();
        $sector = Sector::factory()->create();

        Asset::factory()->create(['category_id' => $cat1->id, 'sector_id' => $sector->id]);
        Asset::factory()->create(['category_id' => $cat2->id, 'sector_id' => $sector->id]);
        Asset::factory()->create(['category_id' => $cat1->id, 'sector_id' => $sector->id]);

        $user = $this->createAdminUser();
        Sanctum::actingAs($user);

        // Act
        $response = $this->getJson("/api/assets?category_id={$cat1->id}");

        // Assert
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_can_search_assets()
    {
        // Arrange
        $category = Category::factory()->create();
        $sector = Sector::factory()->create();
        Asset::factory()->create([
            'name' => 'Dell Laptop',
            'sector_id' => $sector->id,
            'category_id' => $category->id,
        ]);
        Asset::factory()->create([
            'name' => 'Apple Mac',
            'sector_id' => $sector->id,
            'category_id' => $category->id,
        ]);

        $user = $this->createAdminUser();
        Sanctum::actingAs($user);

        // Act
        $response = $this->getJson('/api/assets?search=Dell');

        // Assert
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }
}
