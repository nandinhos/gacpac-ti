<?php

namespace App\Livewire\Users;

use App\Models\MilitaryUser;
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
            MilitaryUser::find($this->confirmingDelete)?->delete();
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
        return view('livewire.users.index', [
            'users' => MilitaryUser::where('name', 'like', '%'.$this->search.'%')
                ->orWhere('email', 'like', '%'.$this->search.'%')
                ->orWhere('military_id', 'like', '%'.$this->search.'%')
                ->paginate(10),
        ])->layout('layouts.sgaiti');
    }
}
