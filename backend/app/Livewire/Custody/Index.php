<?php

namespace App\Livewire\Custody;

use App\Models\CustodyLog;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function delete(CustodyLog $custody)
    {
        $custody->delete();
    }

    public function render()
    {
        return view('livewire.custody.index', [
            'custodyLogs' => CustodyLog::with('user')
                ->where('cautela_number', 'like', '%'.$this->search.'%')
                ->latest()
                ->paginate(10),
        ])->layout('layouts.app');
    }
}
