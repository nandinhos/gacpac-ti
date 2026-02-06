<?php

namespace Tests\Feature;

use App\Livewire\Maintenance\Create;
use App\Livewire\Maintenance\Index;
use App\Models\Asset;
use App\Models\MaintenanceRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MaintenanceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function can_view_maintenance_list_for_asset()
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        MaintenanceRecord::factory()->count(3)->create(['asset_id' => $asset->id]);

        Livewire::actingAs($user)
            ->test(Index::class, ['asset' => $asset])
            ->assertStatus(200)
            ->assertSeeInOrder(['Total de Registros', '3']);
    }

    #[Test]
    public function can_create_corrective_maintenance()
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        Livewire::actingAs($user)
            ->test(Create::class, ['asset' => $asset])
            ->set('type', 'corretiva')
            ->set('date', '2026-02-06')
            ->set('description', 'Troca de teclado com defeito')
            ->set('performed_by', 'SGT Silva')
            ->set('cost', '150.00')
            ->call('save')
            ->assertRedirect(route('maintenance.index', $asset));

        $this->assertDatabaseHas('maintenance_records', [
            'asset_id' => $asset->id,
            'type' => 'corretiva',
            'description' => 'Troca de teclado com defeito',
            'performed_by' => 'SGT Silva',
        ]);
    }

    #[Test]
    public function can_create_preventive_maintenance_with_next_date()
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        Livewire::actingAs($user)
            ->test(Create::class, ['asset' => $asset])
            ->set('type', 'preventiva')
            ->set('date', '2026-02-06')
            ->set('description', 'Limpeza interna e troca de pasta térmica')
            ->set('performed_by', 'CB Oliveira')
            ->set('cost', '50.00')
            ->set('next_maintenance_date', '2026-08-06')
            ->set('notes', 'Verificar cooler na próxima manutenção')
            ->call('save')
            ->assertRedirect(route('maintenance.index', $asset));

        $this->assertDatabaseHas('maintenance_records', [
            'asset_id' => $asset->id,
            'type' => 'preventiva',
        ]);

        $record = MaintenanceRecord::where('asset_id', $asset->id)->first();
        $this->assertEquals('2026-08-06', $record->next_maintenance_date->format('Y-m-d'));
    }

    #[Test]
    public function validates_required_fields()
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        Livewire::actingAs($user)
            ->test(Create::class, ['asset' => $asset])
            ->set('description', '')
            ->set('performed_by', '')
            ->call('save')
            ->assertHasErrors(['description', 'performed_by']);
    }

    #[Test]
    public function validates_type_must_be_valid()
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        Livewire::actingAs($user)
            ->test(Create::class, ['asset' => $asset])
            ->set('type', 'invalido')
            ->set('date', '2026-02-06')
            ->set('description', 'Teste')
            ->set('performed_by', 'Teste')
            ->call('save')
            ->assertHasErrors(['type']);
    }

    #[Test]
    public function validates_next_maintenance_date_must_be_after_date()
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        Livewire::actingAs($user)
            ->test(Create::class, ['asset' => $asset])
            ->set('type', 'preventiva')
            ->set('date', '2026-02-06')
            ->set('description', 'Teste')
            ->set('performed_by', 'Teste')
            ->set('next_maintenance_date', '2026-01-01')
            ->call('save')
            ->assertHasErrors(['next_maintenance_date']);
    }

    #[Test]
    public function can_delete_maintenance_record()
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create();
        $record = MaintenanceRecord::factory()->create(['asset_id' => $asset->id]);

        Livewire::actingAs($user)
            ->test(Index::class, ['asset' => $asset])
            ->call('delete', $record->id);

        $this->assertDatabaseMissing('maintenance_records', ['id' => $record->id]);
    }

    #[Test]
    public function can_filter_by_type()
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        MaintenanceRecord::factory()->create([
            'asset_id' => $asset->id,
            'type' => 'preventiva',
            'description' => 'Manutenção preventiva',
        ]);
        MaintenanceRecord::factory()->create([
            'asset_id' => $asset->id,
            'type' => 'corretiva',
            'description' => 'Manutenção corretiva',
        ]);

        Livewire::actingAs($user)
            ->test(Index::class, ['asset' => $asset])
            ->set('typeFilter', 'preventiva')
            ->assertSee('Manutenção preventiva')
            ->assertDontSee('Manutenção corretiva');
    }

    #[Test]
    public function shows_total_cost_summary()
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        MaintenanceRecord::factory()->create(['asset_id' => $asset->id, 'cost' => 100.00]);
        MaintenanceRecord::factory()->create(['asset_id' => $asset->id, 'cost' => 250.50]);

        Livewire::actingAs($user)
            ->test(Index::class, ['asset' => $asset])
            ->assertSee('350,50');
    }

    #[Test]
    public function overdue_scope_returns_past_due_records()
    {
        $asset = Asset::factory()->create();

        $overdue = MaintenanceRecord::factory()->create([
            'asset_id' => $asset->id,
            'next_maintenance_date' => now()->subDays(5),
        ]);

        MaintenanceRecord::factory()->create([
            'asset_id' => $asset->id,
            'next_maintenance_date' => now()->addDays(15),
        ]);

        MaintenanceRecord::factory()->create([
            'asset_id' => $asset->id,
            'next_maintenance_date' => null,
        ]);

        $results = MaintenanceRecord::overdue()->get();

        $this->assertCount(1, $results);
        $this->assertEquals($overdue->id, $results->first()->id);
    }

    #[Test]
    public function upcoming_scope_returns_next_30_days()
    {
        $asset = Asset::factory()->create();

        MaintenanceRecord::factory()->create([
            'asset_id' => $asset->id,
            'next_maintenance_date' => now()->subDays(5),
        ]);

        $upcoming = MaintenanceRecord::factory()->create([
            'asset_id' => $asset->id,
            'next_maintenance_date' => now()->addDays(15),
        ]);

        MaintenanceRecord::factory()->create([
            'asset_id' => $asset->id,
            'next_maintenance_date' => now()->addDays(60),
        ]);

        $results = MaintenanceRecord::upcoming(30)->get();

        $this->assertCount(1, $results);
        $this->assertEquals($upcoming->id, $results->first()->id);
    }
}
