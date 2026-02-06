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
    public ?string $cost = null;
    public ?string $next_maintenance_date = null;
    public ?string $notes = null;

    public function mount(Asset $asset)
    {
        $this->asset = $asset;
        $this->date = now()->format('Y-m-d');
    }

    public function save()
    {
        $validated = $this->validate([
            'type' => ['required', 'string', 'in:preventiva,corretiva'],
            'date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:1000'],
            'performed_by' => ['required', 'string', 'max:255'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'next_maintenance_date' => ['nullable', 'date', 'after:date'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['asset_id'] = $this->asset->id;

        MaintenanceRecord::create($validated);

        session()->flash('message', 'Manutenção registrada com sucesso.');

        return redirect()->route('maintenance.index', $this->asset);
    }

    public function render()
    {
        return view('livewire.maintenance.create')
            ->layout('layouts.sgaiti');
    }
}
