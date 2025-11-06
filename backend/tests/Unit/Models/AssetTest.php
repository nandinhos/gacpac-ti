<?php

namespace Tests\Unit\Models;

use App\Models\Asset;
use App\Models\Sector;
use App\Models\AssetPhoto;
use App\Models\MaintenanceRecord;
use App\Models\CustodyLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetTest extends TestCase
{
    use RefreshDatabase;

    public function test_asset_can_be_created_with_valid_data()
    {
        $sector = Sector::factory()->create();
        
        $assetData = [
            'name' => 'Notebook Dell',
            'description' => 'Notebook para uso administrativo',
            'qr_code' => 'QR001',
            'sector_id' => $sector->id,
            'status' => 'Disponível',
            'condition' => 'Bom',
            'acquisition_date' => '2024-01-15',
            'acquisition_value' => 2500.00,
            'brand' => 'Dell',
            'model' => 'Inspiron 15',
            'serial_number' => 'DL123456789'
        ];

        $asset = Asset::create($assetData);

        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertEquals('Notebook Dell', $asset->name);
        $this->assertEquals('QR001', $asset->qr_code);
        $this->assertEquals($sector->id, $asset->sector_id);
    }

    public function test_asset_belongs_to_sector()
    {
        $sector = Sector::factory()->create();
        $asset = Asset::factory()->create(['sector_id' => $sector->id]);

        $this->assertInstanceOf(Sector::class, $asset->sector);
        $this->assertEquals($sector->id, $asset->sector->id);
    }

    public function test_asset_has_many_photos()
    {
        $asset = Asset::factory()->create();
        $photo = AssetPhoto::factory()->create(['asset_id' => $asset->id]);

        $this->assertInstanceOf(AssetPhoto::class, $asset->photos->first());
        $this->assertEquals($photo->id, $asset->photos->first()->id);
    }

    public function test_asset_has_many_maintenance_records()
    {
        $asset = Asset::factory()->create();
        $maintenance = MaintenanceRecord::factory()->create(['asset_id' => $asset->id]);

        $this->assertInstanceOf(MaintenanceRecord::class, $asset->maintenanceRecords->first());
        $this->assertEquals($maintenance->id, $asset->maintenanceRecords->first()->id);
    }

    public function test_asset_qr_code_must_be_unique()
    {
        Asset::factory()->create(['qr_code' => 'QR001']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Asset::factory()->create(['qr_code' => 'QR001']);
    }

    public function test_asset_can_have_custody_logs()
    {
        $asset = Asset::factory()->create();
        $custodyLog = CustodyLog::factory()->create();
        
        $custodyLog->assets()->attach($asset->id);

        $this->assertTrue($asset->custodyLogs()->exists());
        $this->assertEquals($custodyLog->id, $asset->custodyLogs->first()->id);
    }
}