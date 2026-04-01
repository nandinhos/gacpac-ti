<?php

namespace Tests\Feature;

use App\Livewire\Categories\Create;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

// Will be created later

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function can_create_new_category()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('name', 'Hardware')
            ->set('description', 'Category for hardware assets')
            ->call('save')
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', [
            'name' => 'Hardware',
            'description' => 'Category for hardware assets',
        ]);
    }
}
