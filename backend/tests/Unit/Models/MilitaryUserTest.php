<?php

namespace Tests\Unit\Models;

use App\Models\MilitaryUser;
use App\Models\Sector;
use App\Models\CustodyLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MilitaryUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_military_user_can_be_created_with_valid_data()
    {
        $sector = Sector::factory()->create();
        
        $userData = [
            'name' => 'João Silva',
            'rank' => 'Sargento',
            'registration' => '123456',
            'military_id' => '1234567-8',
            'sector_id' => $sector->id,
            'role' => 'user',
            'email' => 'joao.silva@eb.mil.br',
            'password' => Hash::make('password123'),
            'is_active' => true
        ];

        $user = MilitaryUser::create($userData);

        $this->assertInstanceOf(MilitaryUser::class, $user);
        $this->assertEquals('João Silva', $user->name);
        $this->assertEquals('Sargento', $user->rank);
        $this->assertEquals('123456', $user->registration);
        $this->assertTrue($user->is_active);
    }

    public function test_military_user_belongs_to_sector()
    {
        $sector = Sector::factory()->create();
        $user = MilitaryUser::factory()->create(['sector_id' => $sector->id]);

        $this->assertInstanceOf(Sector::class, $user->sector);
        $this->assertEquals($sector->id, $user->sector->id);
    }

    public function test_military_user_has_many_custody_logs()
    {
        $user = MilitaryUser::factory()->create();
        $custody1 = CustodyLog::factory()->create(['user_id' => $user->id]);
        $custody2 = CustodyLog::factory()->create(['user_id' => $user->id]);

        $this->assertCount(2, $user->custodyLogs);
        $this->assertInstanceOf(CustodyLog::class, $user->custodyLogs->first());
    }

    public function test_military_user_registration_must_be_unique()
    {
        MilitaryUser::factory()->create(['registration' => '123456']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        MilitaryUser::factory()->create(['registration' => '123456']);
    }

    public function test_military_user_email_must_be_unique()
    {
        MilitaryUser::factory()->create(['email' => 'test@eb.mil.br']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        MilitaryUser::factory()->create(['email' => 'test@eb.mil.br']);
    }

    public function test_military_user_password_is_hashed()
    {
        $user = MilitaryUser::factory()->create(['password' => Hash::make('password123')]);

        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_military_user_scope_active()
    {
        $activeUser = MilitaryUser::factory()->create(['is_active' => true]);
        $inactiveUser = MilitaryUser::factory()->create(['is_active' => false]);

        $activeCount = MilitaryUser::active()->count();

        $this->assertEquals(1, $activeCount);
    }

    public function test_military_user_scope_by_role()
    {
        $admin = MilitaryUser::factory()->create(['role' => 'admin']);
        $user = MilitaryUser::factory()->create(['role' => 'user']);
        $commission = MilitaryUser::factory()->create(['role' => 'commission']);

        $adminCount = MilitaryUser::byRole('admin')->count();
        $userCount = MilitaryUser::byRole('user')->count();

        $this->assertEquals(1, $adminCount);
        $this->assertEquals(1, $userCount);
    }
}