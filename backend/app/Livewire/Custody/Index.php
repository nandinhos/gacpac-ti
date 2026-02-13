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

    public $confirmingDelete = null;

    public function confirmDelete($id)
    {
        $this->confirmingDelete = $id;
        $this->dispatch('open-confirm-delete-modal');
    }

    public function delete()
    {
        if ($this->confirmingDelete) {
            $custody = CustodyLog::find($this->confirmingDelete);
            if ($custody) {
                DB::transaction(function () use ($custody) {
                    // Restore assets to available if custody is deleted and was open
                    if (!$custody->checkin_date) {
                        $custody->assets()->update(['status' => 'DISPONIVEL']);
                    }
                    $custody->assets()->detach();
                    $custody->delete();
                });
            }
            $this->confirmingDelete = null;
        }
        $this->dispatch('close-confirm-delete-modal');
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = null;
        $this->dispatch('close-confirm-delete-modal');
    }

    public function render()
    {
        return view('livewire.custody.index', [
            'custodyLogs' => CustodyLog::with(['user', 'assets'])
                ->when($this->search, function ($query) {
                    $query->where('cautela_number', 'ilike', '%'.$this->search.'%')
                        ->orWhereHas('user', function ($q) {
                            $q->where('name', 'ilike', '%'.$this->search.'%')
                              ->orWhere('military_id', 'ilike', '%'.$this->search.'%');
                        });
                })
                ->when($this->status === 'open', fn($q) => $q->open())
                ->when($this->status === 'closed', fn($q) => $q->closed())
                ->latest()
                ->paginate(10),
        ])->layout('layouts.sgaiti');
    }
}
