<?php

namespace Tests\Feature;

use App\Livewire\Custody\Create;
use App\Livewire\Custody\Edit;
use App\Livewire\Custody\Index;
use App\Models\Asset;
use App\Models\CustodyLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CustodyModuleTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function custody_index_page_is_displayed()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Index::class)
            ->assertStatus(200)
            ->assertSee(__('Nova Cautela'));
    }

    #[Test]
    public function can_create_custody_with_available_assets()
    {
        $user = User::factory()->create();
        $militaryUser = User::factory()->create();
        $asset = Asset::factory()->create(['status' => 'DISPONIVEL']);

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('user_id', $militaryUser->id)
            ->set('checkout_date', '2025-01-01')
            ->set('selectedAssets', [$asset->id])
            ->set('notes', 'Test Note')
            ->call('save')
            ->assertRedirect(route('custody.index'));

        $this->assertDatabaseHas('custody_logs', [
            'user_id' => $militaryUser->id,
            'notes' => 'Test Note',
        ]);

        $this->assertDatabaseHas('custody_assets', [
            'asset_id' => $asset->id,
        ]);

        // Verify asset status updated
        $this->assertEquals('EM_USO', $asset->fresh()->status);
        $this->assertEquals($militaryUser->id, $asset->fresh()->custodian_user_id);
    }

    #[Test]
    public function cannot_create_custody_with_unavailable_assets()
    {
        $user = User::factory()->create();
        $militaryUser = User::factory()->create();
        $asset = Asset::factory()->create(['status' => 'EM_USO']);

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('user_id', $militaryUser->id)
            ->set('checkout_date', '2025-01-01')
            ->set('selectedAssets', [$asset->id])
            ->call('save')
            ->assertHasErrors(['selectedAssets']);
    }

    #[Test]
    public function can_add_asset_to_open_custody()
    {
        $user = User::factory()->create();
        $custody = CustodyLog::factory()->create();
        $asset = Asset::factory()->create(['status' => 'DISPONIVEL']);

        Livewire::actingAs($user)
            ->test(Edit::class, ['custodyLog' => $custody])
            ->call('addAsset', $asset)
            ->assertHasNoErrors();

        $this->assertTrue($custody->assets->contains($asset));
        $this->assertEquals('EM_USO', $asset->fresh()->status);
    }

    #[Test]
    public function can_remove_asset_from_open_custody()
    {
        $user = User::factory()->create();
        $custody = CustodyLog::factory()->create();
        $asset = Asset::factory()->create(['status' => 'EM_USO', 'custodian_user_id' => $custody->user_id]);
        $custody->assets()->attach($asset);

        Livewire::actingAs($user)
            ->test(Edit::class, ['custodyLog' => $custody])
            ->call('removeAsset', $asset)
            ->assertHasNoErrors();

        $this->assertFalse($custody->assets->contains($asset));
        $this->assertEquals('DISPONIVEL', $asset->fresh()->status);
        $this->assertNull($asset->fresh()->custodian_user_id);
    }

    #[Test]
    public function can_close_custody()
    {
        $user = User::factory()->create();
        $custody = CustodyLog::factory()->create(['checkin_date' => null]);
        $asset = Asset::factory()->create(['status' => 'EM_USO', 'custodian_user_id' => $custody->user_id]);
        $custody->assets()->attach($asset);

        Livewire::actingAs($user)
            ->test(Edit::class, ['custodyLog' => $custody])
            ->call('closeCustody')
            ->assertRedirect(route('custody.index'));

        $this->assertNotNull($custody->fresh()->checkin_date);
        $this->assertEquals('DISPONIVEL', $asset->fresh()->status);
        $this->assertNull($asset->fresh()->custodian_user_id);
    }
}
