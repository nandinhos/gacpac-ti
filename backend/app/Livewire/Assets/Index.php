<?php

namespace App\Livewire\Assets;

use App\Models\Asset;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function delete(Asset $asset)
    {
        $asset->delete();
    }

    public function render()
    {
        return view('livewire.assets.index', [
            'assets' => Asset::with('sector')
                ->where('name', 'like', '%'.$this->search.'%')
                ->orWhere('serial_number', 'like', '%'.$this->search.'%')
                ->orWhere('patrimony_number', 'like', '%'.$this->search.'%')
                ->latest()
                ->paginate(10),
        ])->layout('layouts.app');
    }
}
