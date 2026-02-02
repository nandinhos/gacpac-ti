<?php

namespace Tests\Feature;

use App\Livewire\Inventory\Show;
use App\Models\Asset;
use App\Models\InventoryRecord;
use App\Models\MilitaryUser;
use App\Models\Sector;
use App\Models\InventoryAsset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InventoryBulkActionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(MilitaryUser::factory()->create());
    }

    public function test_can_select_all_pending_assets(): void
    {
        $sector = Sector::factory()->create();
        $inventory = InventoryRecord::factory()->create(["sector_id" => $sector->id]);
        $assets = Asset::factory()->count(3)->create(["sector_id" => $sector->id]);

        Livewire::test(Show::class, ["inventory" => $inventory])
            ->set("selectAllPending", true)
            ->assertCount("selectedPending", 3)
            ->call("bulkFind")
            ->assertSet("selectedPending", [])
            ->assertSet("selectAllPending", false); // Nova asserção

        $this->assertEquals(3, InventoryAsset::where("inventory_id", $inventory->id)->count());
    }

    public function test_can_select_all_found_assets_for_removal(): void
    {
        $sector = Sector::factory()->create();
        $inventory = InventoryRecord::factory()->create(["sector_id" => $sector->id]);
        $assets = Asset::factory()->count(3)->create(["sector_id" => $sector->id]);

        foreach ($assets as $asset) {
            InventoryAsset::create([
                "inventory_id" => $inventory->id,
                "asset_id" => $asset->id,
            ]);
        }

        Livewire::test(Show::class, ["inventory" => $inventory])
            ->set("selectAllFound", true)
            ->assertCount("selectedFound", 3)
            ->call("bulkRemove")
            ->assertSet("selectedFound", [])
            ->assertSet("selectAllFound", false); // Nova asserção

        $this->assertEquals(0, InventoryAsset::where("inventory_id", $inventory->id)->count());
    }
}
