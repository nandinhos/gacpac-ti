<?php

namespace App\Livewire\Assets;

use App\Models\Asset;
use App\Models\Sector;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $sector_id = '';

    #[Url(history: true)]
    public $status = '';

    #[Url(history: true)]
    public $type = '';

    #[Url(history: true)]
    public $sortField = 'created_at';

    #[Url(history: true)]
    public $sortDirection = 'desc';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSectorId()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedType()
    {
        $this->resetPage();
    }

    public $confirmingDelete = null;

    public function confirmDelete($id)
    {
        $this->confirmingDelete = $id;
        $this->dispatch('open-confirm-delete-modal');
    }

    public function delete()
    {
        if ($this->confirmingDelete) {
            Asset::find($this->confirmingDelete)?->delete();
            $this->confirmingDelete = null;
        }
        $this->dispatch('close-confirm-delete-modal');
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = null;
        $this->dispatch('close-confirm-delete-modal');
    }

    public function render()
    {
        return view('livewire.assets.index', [
            'assets' => Asset::with('sector')
                ->where(function ($query) {
                    $query->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('serial_number', 'like', '%'.$this->search.'%')
                        ->orWhere('patrimony_number', 'like', '%'.$this->search.'%')
                        ->orWhere('qr_code', 'like', '%'.$this->search.'%');
                })
                ->when($this->sector_id, function ($query) {
                    $query->where('sector_id', $this->sector_id);
                })
                ->when($this->status, function ($query) {
                    $query->where('status', $this->status);
                })
                ->when($this->type, function ($query) {
                    $query->where('type', $this->type);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10),
            'sectors' => Sector::orderBy('name')->get(),
            'types' => ['COMPUTADOR', 'NOTEBOOK', 'MONITOR', 'IMPRESSORA', 'PERIFERICO', 'REDE', 'NOBREAK', 'OUTRO'],
            'statuses' => ['DISPONIVEL', 'EM_USO', 'MANUTENCAO', 'BAIXADO', 'EXTRAVIADO'],
        ])->layout('layouts.sgaiti');
    }
}
