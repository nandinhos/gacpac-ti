<?php

namespace Tests\Feature;

use App\Livewire\Inventory\Show;
use App\Models\Asset;
use App\Models\InventoryRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InventoryLockTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_cannot_modify_concluded_inventory(): void
    {
        $inventory = InventoryRecord::factory()->create(['status' => 'Concluído']);
        $asset = Asset::factory()->create();

        Livewire::test(Show::class, ['inventory' => $inventory])
            ->set('qrCodeInput', $asset->qr_code)
            ->call('findAsset')
            ->assertSet('qrCodeInput', $asset->qr_code)
            ->assertNotDispatched('asset-found');

        $this->assertDatabaseMissing('inventory_assets', [
            'inventory_id' => $inventory->id,
            'asset_id' => $asset->id,
        ]);
    }

    public function test_cannot_add_uncatalogued_to_concluded_inventory(): void
    {
        $inventory = InventoryRecord::factory()->create(['status' => 'Concluído']);

        Livewire::test(Show::class, ['inventory' => $inventory])
            ->set('uncataloguedDescription', 'Item Extra')
            ->call('addUncatalogued');

        $this->assertDatabaseMissing('uncatalogued_items', [
            'inventory_id' => $inventory->id,
            'description' => 'Item Extra',
        ]);
    }

    public function test_cannot_delete_concluded_inventory(): void
    {
        $inventory = InventoryRecord::factory()->create(['status' => 'Concluído']);

        Livewire::test(\App\Livewire\Inventory\Index::class)
            ->call('delete', $inventory->id)
            ->assertSee('Inventários concluídos não podem ser excluídos.');

        $this->assertDatabaseHas('inventory_records', ['id' => $inventory->id]);
    }
}
