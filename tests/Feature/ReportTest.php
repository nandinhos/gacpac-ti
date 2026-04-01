<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_assets_report_can_be_generated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Asset::factory()->create(['name' => 'Notebook Dell', 'status' => 'active']);

        $response = $this->get(route('reports.assets'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_assets_report_with_filters()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $asset1 = Asset::factory()->create(['name' => 'Notebook Dell', 'status' => 'active']);
        $asset2 = Asset::factory()->create(['name' => 'Cadeira Ergonômica', 'status' => 'archived']);

        $response = $this->get(route('reports.assets', ['status' => 'active']));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        // Não conseguimos assertions fáceis no conteúdo binário do PDF, mas o status 200 é um bom sinal.
    }

    public function test_maintenance_report_can_be_generated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $asset = Asset::factory()->create();

        // Criar registro manualmente caso não tenha factory
        \App\Models\MaintenanceRecord::create([
            'asset_id' => $asset->id,
            'type' => 'preventive',
            'date' => now(),
            'description' => 'Limpeza geral',
            'performed_by' => 'Técnico Teste',
            'cost' => 150.00,
        ]);

        $response = $this->get(route('reports.maintenance'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_term_report_can_be_generated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $militaryUser = \App\Models\User::factory()->create();
        $asset = Asset::factory()->create(['custodian_user_id' => $militaryUser->id]);

        $response = $this->get(route('reports.term', ['user_id' => $militaryUser->id]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
