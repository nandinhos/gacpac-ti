<?php

namespace Tests\Feature;

use App\Models\InventoryRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_can_download_inventory_pdf(): void
    {
        $inventory = InventoryRecord::factory()->create();

        $response = $this->get(route('inventory.pdf', $inventory));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
