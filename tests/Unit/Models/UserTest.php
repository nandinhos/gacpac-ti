<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Sector;
use App\Models\CustodyLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created_with_military_data()
    {
        $sector = Sector::factory()->create();
        
        $userData = [
            'name' => 'João Silva',
            'rank' => 'Sargento',
            'military_id' => '1234567-8',
            'is_military' => true,
            'force' => 'FAB',
            'sector_id' => $sector->id,
            'email' => 'joao.silva@fab.mil.br',
            'password' => Hash::make('password123'),
            'is_active' => true
        ];

        $user = User::create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('João Silva', $user->name);
        $this->assertEquals('Sargento', $user->rank);
        $this->assertEquals('1234567-8', $user->military_id);
        $this->assertTrue($user->is_active);
        $this->assertTrue($user->is_military);
    }

    public function test_user_belongs_to_sector()
    {
        $sector = Sector::factory()->create();
        $user = User::factory()->create(['sector_id' => $sector->id]);

        $this->assertInstanceOf(Sector::class, $user->sector);
        $this->assertEquals($sector->id, $user->sector->id);
    }

    public function test_user_has_many_custody_logs()
    {
        $user = User::factory()->create();
        $custody1 = CustodyLog::factory()->create(['user_id' => $user->id]);
        $custody2 = CustodyLog::factory()->create(['user_id' => $user->id]);

        $this->assertCount(2, $user->custodyLogs);
        $this->assertInstanceOf(CustodyLog::class, $user->custodyLogs->first());
    }

    public function test_user_email_must_be_unique()
    {
        User::factory()->create(['email' => 'test@fab.mil.br']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        User::factory()->create(['email' => 'test@fab.mil.br']);
    }

    public function test_user_password_is_hashed()
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);

        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_user_scope_active()
    {
        User::factory()->active()->create();
        User::factory()->inactive()->create();

        $activeCount = User::active()->count();

        $this->assertEquals(1, $activeCount);
    }

    public function test_user_scope_by_role()
    {
        // Setup Spatie Role
        $role = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($role);
        
        $other = User::factory()->create();

        $adminCount = User::byRole('admin')->count();

        $this->assertEquals(1, $adminCount);
    }
}
