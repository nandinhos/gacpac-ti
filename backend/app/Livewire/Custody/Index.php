<?php

namespace App\Livewire\Custody;

use App\Models\CustodyLog;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $status = ''; // 'open' or 'closed'

    public function delete(CustodyLog $custody)
    {
        DB::transaction(function () use ($custody) {
            // Restore assets to available if custody is deleted and was open
            if (!$custody->checkin_date) {
                $custody->assets()->update(['status' => 'DISPONIVEL']);
            }
            $custody->assets()->detach();
            $custody->delete();
        });
    }

    public function render()
    {
        return view('livewire.custody.index', [
            'custodyLogs' => CustodyLog::with(['user', 'assets'])
                ->when($this->search, function ($query) {
                    $query->where('cautela_number', 'like', '%'.$this->search.'%')
                        ->orWhereHas('user', function ($q) {
                            $q->where('name', 'like', '%'.$this->search.'%')
                              ->orWhere('military_id', 'like', '%'.$this->search.'%');
                        });
                })
                ->when($this->status === 'open', fn($q) => $q->open())
                ->when($this->status === 'closed', fn($q) => $q->closed())
                ->latest()
                ->paginate(10),
        ])->layout('layouts.sgaiti');
    }
}
