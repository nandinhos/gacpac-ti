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
            'sectors' => Sector::where('name', 'like', '%'.$this->search.'%')
                ->orWhere('description', 'like', '%'.$this->search.'%')
                ->withCount(['users' => function ($query) {
                    $query->where('is_active', true);
                }])
                ->with(['users' => function($query) {
                    $query->where('is_active', true);
                }])
                ->paginate(10),
        ])->layout('layouts.sgaiti');
    }
}
