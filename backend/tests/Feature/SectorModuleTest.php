<?php

namespace Tests\Feature;

use App\Models\Sector;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SectorModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_sectors_list_page_is_accessible_by_auth_users()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/sectors')
            ->assertStatus(200);
    }

    public function test_can_create_sector()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Sectors\Create::class)
            ->set('name', 'IT Department')
            ->set('description', 'Information Technology')
            ->set('is_active', true)
            ->call('save')
            ->assertRedirect(route('sectors.index'));

        $this->assertDatabaseHas('sectors', ['name' => 'IT Department']);
    }

    public function test_can_edit_sector()
    {
        $user = User::factory()->create();
        $sector = Sector::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Sectors\Edit::class, ['sector' => $sector])
            ->set('name', 'Updated Logistics')
            ->call('save')
            ->assertRedirect(route('sectors.index'));

        $this->assertDatabaseHas('sectors', ['id' => $sector->id, 'name' => 'Updated Logistics']);
    }

    public function test_can_delete_sector()
    {
        $user = User::factory()->create();
        $sector = Sector::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Sectors\Index::class)
            ->call('delete', $sector->id);

        $this->assertDatabaseMissing('sectors', ['id' => $sector->id]);
    }
}
