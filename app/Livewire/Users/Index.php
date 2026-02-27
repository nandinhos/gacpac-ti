<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

use Livewire\Attributes\Title;

#[Title('Usuários')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmingDelete = $id;
    }

    public function delete()
    {
        if ($this->confirmingDelete) {
            User::find($this->confirmingDelete)?->delete(); // Soft delete if trait exists, or hard delete
            $this->confirmingDelete = false;
            session()->flash('message', 'Usuário excluído com sucesso.');
        }
    }

    public function render()
    {
        return view('livewire.users.index', [
            'users' => User::with(['sector'])
                ->where('name', 'like', '%'.$this->search.'%')
                ->orWhere('email', 'like', '%'.$this->search.'%') // Added email search as it is standard for User
                ->paginate(10),
        ])->layout('layouts.sgaiti');
    }
}
