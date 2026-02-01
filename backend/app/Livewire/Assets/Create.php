<?php

namespace App\Livewire\Assets;

use App\Models\Asset;
use App\Models\Sector;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
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

    public function generateQrCode()
    {
        $this->qr_code = 'ASSET-' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function mount()
    {
        $this->generateQrCode();
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'qr_code' => ['required', 'string', 'unique:assets'],
            
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

        Asset::create($validated);

        return redirect()->route('assets.index');
    }

    public function render()
    {
        return view('livewire.assets.create', [
            'sectors' => Sector::where('is_active', true)->orderBy('name')->get(),
            'types' => ['COMPUTADOR', 'MONITOR', 'IMPRESSORA', 'PERIFERICO', 'REDE', 'NOBREAK', 'OUTRO'],
            'statuses' => ['DISPONIVEL', 'EM_USO', 'MANUTENCAO', 'BAIXADO'],
            'conditions' => ['NOVO', 'BOM', 'REGULAR', 'RUIM', 'SUCATA'],
        ])->layout('layouts.app');
    }
}
