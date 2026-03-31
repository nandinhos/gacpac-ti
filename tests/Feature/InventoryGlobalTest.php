<?php

namespace Tests\Feature;

use App\Livewire\Inventory\Create;
use App\Livewire\Inventory\Show;
use App\Models\Asset;
use App\Models\InventoryRecord;
use App\Models\User;
use App\Models\Sector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InventoryGlobalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_can_create_global_inventory(): void
    {
        $responsible = User::factory()->create();

        Livewire::test(Create::class)
            ->set("commission_number", "INV-GLOBAL-001")
            ->set("start_date", "2026-02-01")
            ->set("sector_id", "") // Vazio = Todos
            ->set("responsible_user_id", $responsible->id)
            ->call("save")
            ->assertHasNoErrors();

        $this->assertDatabaseHas("inventory_records", [
            "commission_number" => "INV-GLOBAL-001",
            "sector_id" => null,
        ]);
    }

    public function test_global_inventory_shows_all_assets(): void
    {
        $sector1 = Sector::factory()->create();
        $sector2 = Sector::factory()->create();
        
        $asset1 = Asset::factory()->create(["sector_id" => $sector1->id, "name" => "Asset Sector 1"]);
        $asset2 = Asset::factory()->create(["sector_id" => $sector2->id, "name" => "Asset Sector 2"]);

        $inventory = InventoryRecord::factory()->create([
            "sector_id" => null // Global
        ]);

        Livewire::test(Show::class, ["inventory" => $inventory])
            ->assertSee("Asset Sector 1")
            ->assertSee("Asset Sector 2");
    }
}
