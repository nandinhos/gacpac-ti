<?php

namespace App\Livewire\Sectors;

use App\Models\Sector;
use Livewire\Component;

class Show extends Component
{
    public Sector $sector;

    public function mount(Sector $sector)
    {
        $this->sector = $sector;
    }

    public function render()
    {
        // Carrega usuários do setor
        $this->sector->load(['militaryUsers' => function ($query) {
            $query->active();
        }]);

        // Para cada usuário, buscamos os ativos de setor e cautelas separadamente
        $usersData = $this->sector->militaryUsers->map(function ($user) {
            return [
                'user' => $user,
                'sectorAssets' => $user->assets()->where('sector_id', $this->sector->id)->get(),
                'custodyAssets' => $user->currentCustodyAssets()->get(),
            ];
        });

        return view('livewire.sectors.show', [
            'usersData' => $usersData,
        ])->layout('layouts.sgaiti');
    }
}
