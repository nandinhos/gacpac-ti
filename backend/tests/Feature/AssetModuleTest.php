<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\User;
use App\Models\Sector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use App\Livewire\Assets\Index;
use App\Livewire\Assets\Create;
use App\Livewire\Assets\Edit;
use PHPUnit\Framework\Attributes\Test;

class AssetModuleTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function assets_page_is_displayed_and_component_renders()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Index::class)
            ->assertStatus(200)
            ->assertSee(__('Novo Ativo'));
    }

    #[Test]
    public function can_create_asset_with_required_fields()
    {
        $user = User::factory()->create();
        $sector = Sector::factory()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('name', 'Novo Notebook Dell')
            ->set('brand', 'Dell')
            ->set('model', 'Latitude 5420')
            ->set('qr_code', 'QR-TEST-001')
            ->set('serial_number', 'SN12345678')
            ->set('patrimony_number', 'PAT-001')
            ->set('type', 'NOTEBOOK')
            ->set('category', 'TI')
            ->set('status', 'DISPONIVEL')
            ->set('condition', 'NOVO')
            ->set('sector_id', $sector->id)
            ->set('location', 'Armário 01')
            ->set('acquisition_date', '2025-01-01')
            ->set('warranty_expiry', '2028-01-01')
            ->set('purchase_value', '5500.00')
            ->call('save')
            ->assertRedirect(route('assets.index'));

        // Verificação flexível para o banco de dados
        $this->assertDatabaseHas('assets', [
            'name' => 'Novo Notebook Dell',
            'qr_code' => 'QR-TEST-001',
            'location' => 'Armário 01',
        ]);
        
        $asset = Asset::where('qr_code', 'QR-TEST-001')->first();
        $this->assertStringStartsWith('2028-01-01', (string) $asset->warranty_expiry);
    }

    #[Test]
    public function can_edit_asset()
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create(['name' => 'Old Name']);

        Livewire::actingAs($user)
            ->test(Edit::class, ['asset' => $asset])
            ->set('name', 'Updated Name')
            ->call('save')
            ->assertRedirect(route('assets.edit', $asset));
        
        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'name' => 'Updated Name',
        ]);
    }
}
