<?php

namespace App\Livewire\Custody;

use App\Models\Asset;
use App\Models\CustodyLog;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public CustodyLog $custodyLog;
    public $notes;
    public $cautela_number;
    public $user_name;
    public $checkout_date;
    public $checkin_date;

    public function mount(CustodyLog $custodyLog)
    {
        $this->custodyLog = $custodyLog;
        $this->cautela_number = $custodyLog->cautela_number;
        $this->user_name = $custodyLog->user->rank . ' ' . $custodyLog->user->name;
        $this->checkout_date = $custodyLog->checkout_date ? $custodyLog->checkout_date->format('Y-m-d') : '';
        $this->checkin_date = $custodyLog->checkin_date ? $custodyLog->checkin_date->format('Y-m-d') : null;
        $this->notes = $custodyLog->notes;
    }

    public function closeCustody()
    {
        DB::transaction(function () {
            $this->custodyLog->update([
                'checkin_date' => now(),
            ]);

            // Release assets
            $this->custodyLog->assets()->update(['status' => 'DISPONIVEL']);
        });

        return redirect()->route('custody.index');
    }

    public function render()
    {
        return view('livewire.custody.edit', [
            'assets' => $this->custodyLog->assets,
        ])->layout('layouts.app');
    }
}
