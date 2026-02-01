<?php

namespace App\Livewire\Users;

use App\Models\MilitaryUser;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function delete(\App\Models\MilitaryUser $user)
    {
        $user->delete();
    }

    public function render()
    {
        return view('livewire.users.index', [
            'users' => \App\Models\MilitaryUser::where('name', 'like', '%'.$this->search.'%')
                ->orWhere('email', 'like', '%'.$this->search.'%')
                ->orWhere('military_id', 'like', '%'.$this->search.'%')
                ->paginate(10),
        ])->layout('layouts.sgaiti');
    }
}
