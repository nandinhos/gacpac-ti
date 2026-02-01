<?php

namespace App\Livewire\Sectors;

use App\Models\Sector;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function delete(Sector $sector)
    {
        $sector->delete();
    }

    public function render()
    {
        return view('livewire.sectors.index', [
            'sectors' => Sector::where('name', 'like', '%'.$this->search.'%')
                ->orWhere('description', 'like', '%'.$this->search.'%')
                ->paginate(10),
        ])->layout('layouts.sgaiti');
    }
}
