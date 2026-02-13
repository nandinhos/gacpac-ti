<?php

namespace App\Livewire\Maintenance;

use App\Models\Asset;
use App\Models\MaintenanceRecord;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.sgaiti')]
class Index extends Component
{
    use WithPagination;

    public Asset $asset;
    public bool $isEmbedded = false;

    public $search = '';
    public $typeFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => ''],
    ];

    public function mount(Asset $asset, bool $isEmbedded = false)
    {
        $this->asset = $asset;
        $this->isEmbedded = $isEmbedded;
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
        $this->dispatch('maintenance-deleted');
        session()->flash('message', 'Registro de manutenção excluído com sucesso.');
    }

    public function render()
    {
        $query = MaintenanceRecord::where('asset_id', $this->asset->id);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('description', 'ilike', '%' . $this->search . '%')
                  ->orWhere('performed_by', 'ilike', '%' . $this->search . '%')
                  ->orWhere('notes', 'ilike', '%' . $this->search . '%');
            });
        }

        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        $records = $query->orderByDesc('date')->paginate(10);

        return view('livewire.maintenance.index', [
            'records' => $records,
        ]);
    }
}
