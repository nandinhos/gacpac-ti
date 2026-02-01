<?php

namespace App\Livewire\Assets;

use App\Models\Asset;
use App\Models\Sector;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    public Asset $asset;

    // Basic Info
    public string $name = '';
    public string $brand = '';
    public string $model = '';
    public string $qr_code = '';
    
    // Identification
    public string $serial_number = '';
    public string $patrimony_number = '';
    
    // Classification
    public string $type = 'COMPUTADOR';
    public string $category = 'TI';
    
    // Status & Location
    public string $status = 'DISPONIVEL';
    public string $condition = 'NOVO';
    public ?int $sector_id = null;
    
    // Financial
    public ?string $acquisition_date = null;
    public ?string $purchase_value = null;
    public ?string $notes = null;

    public function mount(Asset $asset)
    {
        $this->asset = $asset;
        $this->name = $asset->name;
        $this->brand = $asset->brand ?? '';
        $this->model = $asset->model ?? '';
        $this->qr_code = $asset->qr_code;
        $this->serial_number = $asset->serial_number ?? '';
        $this->patrimony_number = $asset->patrimony_number ?? '';
        $this->type = $asset->type ?? 'COMPUTADOR';
        $this->category = $asset->category ?? 'TI';
        $this->status = $asset->status;
        $this->condition = $asset->condition ?? 'NOVO';
        $this->sector_id = $asset->sector_id;
        $this->acquisition_date = $asset->acquisition_date ? $asset->acquisition_date->format('Y-m-d') : null;
        $this->purchase_value = $asset->purchase_value;
        $this->notes = $asset->notes;
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'qr_code' => ['required', 'string', Rule::unique('assets')->ignore($this->asset->id)],
            
            'serial_number' => ['nullable', 'string', 'max:255'],
            'patrimony_number' => ['nullable', 'string', 'max:255'],
            
            'type' => ['required', 'string'],
            'category' => ['required', 'string'],
            
            'status' => ['required', 'string'],
            'condition' => ['required', 'string'],
            'sector_id' => ['nullable', 'exists:sectors,id'],
            
            'acquisition_date' => ['nullable', 'date'],
            'purchase_value' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->asset->update($validated);

        return redirect()->route('assets.index');
    }

    public function render()
    {
        return view('livewire.assets.edit', [
            'sectors' => Sector::where('is_active', true)->orderBy('name')->get(),
            'types' => ['COMPUTADOR', 'MONITOR', 'IMPRESSORA', 'PERIFERICO', 'REDE', 'NOBREAK', 'OUTRO'],
            'statuses' => ['DISPONIVEL', 'EM_USO', 'MANUTENCAO', 'BAIXADO'],
            'conditions' => ['NOVO', 'BOM', 'REGULAR', 'RUIM', 'SUCATA'],
        ])->layout('layouts.app');
    }
}
