<?php

namespace App\Livewire\Maintenance;

use App\Models\Asset;
use App\Models\MaintenanceRecord;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public Asset $asset;

    public $search = '';
    public $typeFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => ''],
    ];

    public function mount(Asset $asset)
    {
        $this->asset = $asset;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $record = MaintenanceRecord::where('asset_id', $this->asset->id)->findOrFail($id);
        $record->delete();
        session()->flash('message', 'Registro de manutenção excluído com sucesso.');
    }

    public function render()
    {
        $query = MaintenanceRecord::where('asset_id', $this->asset->id);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhere('performed_by', 'like', '%' . $this->search . '%')
                  ->orWhere('notes', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        $records = $query->orderByDesc('date')->paginate(10);

        $totalCost = MaintenanceRecord::where('asset_id', $this->asset->id)->sum('cost');

        return view('livewire.maintenance.index', [
            'records' => $records,
            'totalCost' => $totalCost,
        ])->layout('layouts.sgaiti');
    }
}
