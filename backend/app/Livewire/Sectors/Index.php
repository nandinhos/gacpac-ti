<?php

namespace App\Livewire\Sectors;

use App\Models\Sector;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDelete = null;

    public function confirmDelete($id)
    {
        $this->confirmingDelete = $id;
        $this->dispatch('open-confirm-delete-modal');
    }

    public function delete()
    {
        if ($this->confirmingDelete) {
            Sector::find($this->confirmingDelete)?->delete();
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
        return view('livewire.sectors.index', [
            'sectors' => Sector::where('name', 'ilike', '%'.$this->search.'%')
                ->orWhere('description', 'ilike', '%'.$this->search.'%')
                ->withCount('militaryUsers')
                ->with(['militaryUsers' => function($query) {
                    $query->active();
                }])
                ->paginate(10),
        ])->layout('layouts.sgaiti');
    }
}
