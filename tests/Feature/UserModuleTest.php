<?php

namespace Tests\Feature;

use App\Models\Sector;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_list_page_is_accessible_by_auth_users()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/users')
            ->assertStatus(200);
    }

    public function test_users_list_component_renders_correctly()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Users\Index::class)
            ->assertStatus(200)
            ->assertViewIs('livewire.users.index');
    }

    public function test_can_create_user()
    {
        $user = User::factory()->create();
        $sector = Sector::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Users\Create::class)
            ->set('rank', 'Soldado')
            ->set('name', 'New Military User')
            ->set('military_id', '123456-7')
            ->set('email', 'new_military@example.com')
            ->set('sector_id', $sector->id)
            ->call('save')
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', ['email' => 'new_military@example.com']);
    }

    public function test_can_edit_user()
    {
        $user = User::factory()->military()->create();
        $targetUser = User::factory()->military()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Users\Edit::class, ['user' => $targetUser])
            ->set('name', 'Updated Military Name')
            ->call('update')
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', ['id' => $targetUser->id, 'name' => 'Updated Military Name']);
    }

    public function test_can_delete_user()
    {
        $user = User::factory()->create();
        $targetUser = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Users\Index::class)
            ->call('confirmDelete', $targetUser->id)
            ->assertSet('confirmingDelete', $targetUser->id)
            ->call('delete');

        $this->assertDatabaseMissing('users', ['id' => $targetUser->id]);
    }
}
