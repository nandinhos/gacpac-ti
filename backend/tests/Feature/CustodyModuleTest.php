<?php

namespace Tests\Feature;

use App\Models\CustodyLog;
use App\Models\MilitaryUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CustodyModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_custody_list_page_is_accessible_by_auth_users()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/custody')
            ->assertStatus(200);
    }

    public function test_custody_model_relationship()
    {
       // Basic model test
       $user = MilitaryUser::factory()->create();
       $custody = CustodyLog::factory()->create([
           'user_id' => $user->id,
           'cautela_number' => 'CAUTELA-TEST-001'
       ]);

       $this->assertInstanceOf(MilitaryUser::class, $custody->user);
       $this->assertEquals($user->id, $custody->user->id);
    }

    public function test_can_create_custody_with_assets()
    {
        $user = User::factory()->create();
        $military = MilitaryUser::factory()->create();
        $asset = \App\Models\Asset::factory()->create(['status' => 'DISPONIVEL']);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Custody\Create::class)
            ->set('user_id', $military->id)
            ->set('checkout_date', now()->format('Y-m-d'))
            ->set('selectedAssets', [$asset->id])
            ->call('save')
            ->assertRedirect(route('custody.index'));

        $this->assertDatabaseHas('custody_logs', [
            'user_id' => $military->id,
        ]);

        $this->assertDatabaseHas('custody_assets', [
            'asset_id' => $asset->id,
        ]);
        
        // Verify asset status changed to EM_USO
        $this->assertEquals('EM_USO', $asset->fresh()->status);
    }

    public function test_can_close_custody()
    {
        $user = User::factory()->create();
        $military = MilitaryUser::factory()->create();
        $asset = \App\Models\Asset::factory()->create(['status' => 'EM_USO']);
        
        $custody = \App\Models\CustodyLog::factory()->create([
            'user_id' => $military->id,
            'checkout_date' => now()->subDays(5),
        ]);
        $custody->assets()->attach($asset->id);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Custody\Edit::class, ['custodyLog' => $custody])
            ->call('closeCustody')
            ->assertRedirect(route('custody.index')); 

        $this->assertNotNull($custody->fresh()->checkin_date);
        $this->assertEquals('DISPONIVEL', $asset->fresh()->status);
    }
}
