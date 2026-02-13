<?php

namespace App\Livewire\Users;

use App\Models\MilitaryUser;
use Livewire\Component;

class Show extends Component
{
    public MilitaryUser $user;

    public function mount(MilitaryUser $user)
    {
        // Eager loading das relações necessárias
        $this->user = $user->load(['sector', 'assets']);
    }

    public function render()
    {
        // Busca ativos sob cautela ativa
        $custodyAssets = $this->user->currentCustodyAssets()->get();

        return view('livewire.users.show', [
            'custodyAssets' => $custodyAssets
        ])->layout('layouts.sgaiti');
    }
}
