<?php

namespace App\Livewire\Custody;

use App\Models\CustodyLog;
use Livewire\Component;

class PrintCautela extends Component
{
    public CustodyLog $custodyLog;

    public function mount(CustodyLog $custodyLog)
    {
        $this->custodyLog = $custodyLog->load(['user.sector', 'assets']);
    }

    public function render()
    {
        return view('livewire.custody.print-cautela')
            ->layout('layouts.print');
    }
}
