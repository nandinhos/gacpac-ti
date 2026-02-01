<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function delete(User $user)
    {
        if ($user->id === auth()->id()) {
            return;
        }

        $user->delete();
    }

    public function render()
    {
        return view('livewire.users.index', [
            'users' => User::where('name', 'like', '%'.$this->search.'%')
                ->orWhere('email', 'like', '%'.$this->search.'%')
                ->paginate(10),
        ])->layout('layouts.app');
    }
}
