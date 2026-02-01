<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AssetModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_assets_list_page_is_accessible_by_auth_users()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/assets')
            ->assertStatus(200);
    }

    public function test_can_create_asset()
    {
        $user = User::factory()->create();
        $sector = Sector::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Assets\Create::class)
            ->set('name', 'Dell Latitude 5420')
            ->set('brand', 'Dell')
            ->set('model', 'Latitude 5420')
            ->set('qr_code', 'ASSET-TEST-001')
            ->set('type', 'COMPUTADOR')
            ->set('category', 'TI')
            ->set('status', 'DISPONIVEL')
            ->set('condition', 'NOVO')
            ->set('sector_id', $sector->id)
            ->call('save')
            ->assertRedirect(route('assets.index'));

        $this->assertDatabaseHas('assets', [
            'name' => 'Dell Latitude 5420',
            'qr_code' => 'ASSET-TEST-001',
            'sector_id' => $sector->id,
        ]);
    }

    public function test_can_edit_asset()
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create();
        $newSector = Sector::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Assets\Edit::class, ['asset' => $asset])
            ->set('name', 'Updated Asset Name')
            ->set('sector_id', $newSector->id)
            ->call('save')
            ->assertRedirect(route('assets.index'));

        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'name' => 'Updated Asset Name',
            'sector_id' => $newSector->id,
        ]);
    }

    public function test_can_delete_asset()
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Assets\Index::class)
            ->call('delete', $asset->id);

        $this->assertDatabaseMissing('assets', ['id' => $asset->id]);
    }
}
