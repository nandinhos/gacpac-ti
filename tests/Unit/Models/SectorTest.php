<?php

namespace Tests\Unit\Models;

use App\Models\Sector;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SectorTest extends TestCase
{
    use RefreshDatabase;

    public function test_sector_can_be_created_with_valid_data()
    {
        $sectorData = [
            'name' => 'Seção de Informática',
            'code' => 'SI',
            'description' => 'Responsável pela manutenção dos equipamentos de TI',
            'is_active' => true
        ];

        $sector = Sector::create($sectorData);

        $this->assertInstanceOf(Sector::class, $sector);
        $this->assertEquals('Seção de Informática', $sector->name);
        $this->assertEquals('SI', $sector->code);
        $this->assertTrue($sector->is_active);
    }

    public function test_sector_has_many_assets()
    {
        $sector = Sector::factory()->create();
        $asset1 = Asset::factory()->create(['sector_id' => $sector->id]);
        $asset2 = Asset::factory()->create(['sector_id' => $sector->id]);

        $this->assertCount(2, $sector->assets);
        $this->assertInstanceOf(Asset::class, $sector->assets->first());
    }

    public function test_sector_has_many_users()
    {
        $sector = Sector::factory()->create();
        $user1 = User::factory()->create(['sector_id' => $sector->id]);
        $user2 = User::factory()->create(['sector_id' => $sector->id]);

        $this->assertCount(2, $sector->users);
        $this->assertInstanceOf(User::class, $sector->users->first());
    }

    public function test_sector_code_must_be_unique()
    {
        Sector::factory()->create(['code' => 'SI']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Sector::factory()->create(['code' => 'SI']);
    }

    public function test_sector_can_be_soft_deleted()
    {
        $sector = Sector::factory()->create();
        $sectorId = $sector->id;

        $sector->delete();

        $this->assertSoftDeleted('sectors', ['id' => $sectorId]);
        $this->assertCount(0, Sector::all());
        $this->assertCount(1, Sector::withTrashed()->get());
    }

    public function test_sector_scope_active()
    {
        $activeSector = Sector::factory()->create(['is_active' => true]);
        $inactiveSector = Sector::factory()->create(['is_active' => false]);

        $activeCount = Sector::active()->count();

        $this->assertEquals(1, $activeCount);
    }
}