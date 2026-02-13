<?php

namespace App\Livewire\Maintenance;

use App\Models\Asset;
use App\Models\MaintenanceRecord;
use Livewire\Component;

class Create extends Component
{
    public Asset $asset;

    public string $type = 'corretiva';
    public string $date = '';
    public string $description = '';
    public string $performed_by = '';
    public ?string $next_maintenance_date = null;
    public ?string $notes = null;
    public bool $is_upgrade = false;
    public ?string $parts_replaced = null;
    public ?int $sector_id = null;

    public function mount(Asset $asset)
    {
        $this->asset = $asset;
        $this->date = now()->format('Y-m-d');
        // Setor padrão de manutenção: ATI (ID 2)
        $this->sector_id = 2; 
    }

    public function save()
    {
        $validated = $this->validate([
            'type' => ['required', 'string', 'in:preventiva,corretiva'],
            'date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:1000'],
            'performed_by' => ['required', 'string', 'max:255'],
            'next_maintenance_date' => ['nullable', 'date', 'after:date'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'is_upgrade' => ['boolean'],
            'parts_replaced' => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['asset_id'] = $this->asset->id;
        $validated['cost'] = 0; // Custo zero conforme solicitado (equipe própria)

        MaintenanceRecord::create($validated);

        // Lógica de Status, Setor e Modificação
        $updateData = [
            'status' => 'MANUTENCAO',
            'sector_id' => $this->sector_id
        ];

        if ($this->is_upgrade) {
            $updateData['is_modified'] = true;
        }

        $this->asset->update($updateData);

        session()->flash('message', 'Manutenção registrada com sucesso. O ativo foi movido para o setor de TI e seu status alterado para MANUTENÇÃO.');

        return redirect()->route('assets.edit', ['asset' => $this->asset, 'tab' => 'manutencao']);
    }

    public function render()
    {
        return view('livewire.maintenance.create', [
            'sectors' => \App\Models\Sector::where('is_active', true)->orderBy('name')->get(),
        ])->layout('layouts.sgaiti');
    }
}
