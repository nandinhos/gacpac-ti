<?php

namespace App\Livewire\Inventory;

use App\Models\InventoryRecord;
use App\Models\Sector;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $sector_id = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'sector_id' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(InventoryRecord $inventory)
    {
        $inventory->delete();
        session()->flash('message', 'Inventário excluído com sucesso.');
    }

    public function reopen(InventoryRecord $inventory)
    {
        $inventory->update([
            'status' => 'Reaberto',
            'end_date' => null,
        ]);
        session()->flash('message', 'Inventário reaberto com sucesso.');
    }

    public function render()
    {
        $inventories = InventoryRecord::with(['sector', 'responsibleUser'])
            ->when($this->search, function ($query) {
                $query->where('commission_number', 'like', '%' . $this->search . '%');
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->sector_id, function ($query) {
                $query->where('sector_id', $this->sector_id);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.inventory.index', [
            'inventories' => $inventories,
            'sectors' => Sector::orderBy('name')->get(),
            'statuses' => ['Em Andamento', 'Concluído', 'Reaberto'],
        ])->layout('layouts.sgaiti');
    }
}
