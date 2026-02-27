<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserUnificationTest extends TestCase
{
    // use RefreshDatabase; // Comentado para não limpar o banco se o user quiser preservar dados, mas idealmente deve usar.
    // O user está usando um banco persistente? O ambiente é docker.
    // Melhor usar RefreshDatabase para garantir isolamento.
    use RefreshDatabase;

    public function test_it_can_create_a_military_user()
    {
        $user = User::factory()->create([
            'is_military' => true,
            'force' => 'Exército',
            'rank' => 'Capitão',
            'military_id' => '123456789',
            'organization' => 'GAC-PAC',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_military' => true,
            'force' => 'Exército',
            'rank' => 'Capitão',
            'military_id' => '123456789',
        ]);
    }

    public function test_it_can_create_a_civilian_user()
    {
        $user = User::factory()->create([
            'is_military' => false,
            'force' => null,
            'rank' => null,
            'military_id' => null,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_military' => false,
            'force' => null,
            'rank' => null,
        ]);
    }
}
