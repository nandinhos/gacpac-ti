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

    public function delete($id)
    {
        $inventory = InventoryRecord::find($id);

        if ($inventory) {
            if ($inventory->status === 'Concluído') {
                session()->flash('message', 'Inventários concluídos não podem ser excluídos.');

                return;
            }

            $inventory->delete();
            session()->flash('message', 'Inventário excluído com sucesso.');
        }
    }

    public $reopenId = null;

    public $reopenJustification = '';

    public $showReopenModal = false;

    public function openReopenModal($id)
    {
        $this->reopenId = $id;
        $this->reopenJustification = '';
        $this->showReopenModal = true;
        $this->dispatch('open-modal', 'reopen-modal');
    }

    public function confirmReopen()
    {
        $this->validate([
            'reopenJustification' => 'required|string|min:5|max:255',
        ]);

        $inventory = InventoryRecord::find($this->reopenId);

        if ($inventory) {
            $inventory->update([
                'status' => 'Reaberto',
                'end_date' => null,
            ]);

            \App\Models\ReopenHistory::create([
                'inventory_id' => $inventory->id,
                'reopened_by_user_id' => auth()->id(),
                'reopened_at' => now(),
                'justification' => $this->reopenJustification,
            ]);

            session()->flash('message', 'Inventário reaberto com sucesso.');
        }

        $this->showReopenModal = false;
        $this->reopenId = null;
        $this->dispatch('close-modal', 'reopen-modal');
    }

    public function closeReopenModal()
    {
        $this->showReopenModal = false;
        $this->reopenId = null;
        $this->dispatch('close-modal', 'reopen-modal');
    }

    public function render()
    {
        $inventories = InventoryRecord::with(['sector', 'responsibleUser'])
            ->when($this->search, function ($query) {
                $query->where('commission_number', 'like', '%'.$this->search.'%');
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
