<?php

namespace Tests\Feature;

use App\Livewire\Inventory\Create;
use App\Livewire\Inventory\Index;
use App\Livewire\Inventory\Show;
use App\Models\Asset;
use App\Models\InventoryRecord;
use App\Models\MilitaryUser;
use App\Models\Sector;
use App\Models\InventoryAsset;
use App\Models\UncataloguedItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InventoryFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(MilitaryUser::factory()->create());
    }

    public function test_can_view_inventory_index(): void
    {
        InventoryRecord::factory()->create(['commission_number' => 'INV-001']);

        Livewire::test(Index::class)
            ->assertStatus(200)
            ->assertSee('INV-001');
    }

    public function test_can_view_inventory_show(): void
    {
        $inventory = InventoryRecord::factory()->create();

        Livewire::test(Show::class, ['inventory' => $inventory])
            ->assertStatus(200)
            ->assertSee('Pendentes')
            ->assertSee('Conferidos')
            ->assertSee('Não Catalogados');
    }

    public function test_can_find_asset_by_qr_code(): void
    {
        $sector = Sector::factory()->create();
        $inventory = InventoryRecord::factory()->create(['sector_id' => $sector->id]);
        $asset = Asset::factory()->create(['qr_code' => 'QR123', 'sector_id' => $sector->id]);

        Livewire::test(Show::class, ['inventory' => $inventory])
            ->set('qrCodeInput', 'QR123')
            ->call('findAsset')
            ->assertDispatched('asset-found')
            ->assertSet('qrCodeInput', '');

        $this->assertTrue(InventoryAsset::where('inventory_id', $inventory->id)->where('asset_id', $asset->id)->exists());
    }

    public function test_can_add_uncatalogued_item(): void
    {
        $inventory = InventoryRecord::factory()->create();

        Livewire::test(Show::class, ['inventory' => $inventory])
            ->set('uncataloguedDescription', 'Item Extra')
            ->call('addUncatalogued')
            ->assertSet('uncataloguedDescription', '');

        $this->assertTrue(UncataloguedItem::where('inventory_id', $inventory->id)->where('description', 'Item Extra')->exists());
    }

    public function test_can_finalize_inventory(): void
    {
        $inventory = InventoryRecord::factory()->create(['status' => 'Em Andamento']);

        Livewire::test(Show::class, ['inventory' => $inventory])
            ->set('notes', 'Auditoria OK')
            ->call('finalize')
            ->assertRedirect(route('inventory.index'));

        $inventory->refresh();
        $this->assertEquals('Concluído', $inventory->status);
        $this->assertEquals('Auditoria OK', $inventory->notes);
        $this->assertNotNull($inventory->end_date);
    }

    public function test_can_view_inventory_create(): void
    {
        Sector::factory()->create();
        MilitaryUser::factory()->create();

        Livewire::test(Create::class)
            ->assertStatus(200)
            ->assertSee('Número da Comissão')
            ->assertSee('Setor a Inventariar')
            ->assertSee('Militar Responsável');
    }

    public function test_can_create_inventory(): void
    {
        $sector = Sector::factory()->create();
        $responsible = MilitaryUser::factory()->create();

        Livewire::test(Create::class)
            ->set('commission_number', 'INV-TEST-001')
            ->set('start_date', '2026-02-01')
            ->set('sector_id', $sector->id)
            ->set('responsible_user_id', $responsible->id)
            ->set('notes', 'Inventário de teste')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('inventory_records', [
            'commission_number' => 'INV-TEST-001',
            'sector_id' => $sector->id,
            'responsible_user_id' => $responsible->id,
            'status' => 'Em Andamento',
        ]);
    }

    public function test_create_inventory_validates_required_fields(): void
    {
        Livewire::test(Create::class)
            ->set('commission_number', '')
            ->set('sector_id', '')
            ->set('responsible_user_id', '')
            ->call('save')
            ->assertHasErrors(['commission_number', 'sector_id', 'responsible_user_id']);
    }

    public function test_create_inventory_validates_unique_commission_number(): void
    {
        $sector = Sector::factory()->create();
        $responsible = MilitaryUser::factory()->create();
        InventoryRecord::factory()->create(['commission_number' => 'INV-DUPLICATE']);

        Livewire::test(Create::class)
            ->set('commission_number', 'INV-DUPLICATE')
            ->set('start_date', '2026-02-01')
            ->set('sector_id', $sector->id)
            ->set('responsible_user_id', $responsible->id)
            ->call('save')
            ->assertHasErrors(['commission_number']);
    }
}
