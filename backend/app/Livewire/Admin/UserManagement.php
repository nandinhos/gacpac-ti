<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $isEditing = false;
    public $editingUser;
    public $selectedRole = '';

    protected $rules = [
        'selectedRole' => 'required|exists:roles,name',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function edit($id)
    {
        $this->editingUser = User::findOrFail($id);
        $this->selectedRole = $this->editingUser->roles->first()->name ?? '';
        $this->isEditing = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingUser->id === Auth::id() && $this->selectedRole !== 'admin') {
            session()->flash('error', 'Você não pode remover seu próprio privilégio de admin.');
            return;
        }

        $this->editingUser->syncRoles([$this->selectedRole]);
        
        $this->isEditing = false;
        $this->editingUser = null;
        session()->flash('message', 'Função atualizada com sucesso.');
    }

    public function cancel()
    {
        $this->isEditing = false;
        $this->editingUser = null;
    }

    public function render()
    {
        $users = User::query()
            ->with('roles')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users,
            'roles' => Role::all(),
        ])->layout('layouts.sgaiti');
    }
}
