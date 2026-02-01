<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Asset;
use App\Models\CustodyLog;
use App\Models\MilitaryUser;
use App\Models\Sector;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard', [
            'totalAssets' => Asset::count(),
            'assetsInUse' => Asset::where('status', 'EM_USO')->count(),
            'activeCustodies' => CustodyLog::open()->count(),
            'totalMilUsers' => MilitaryUser::count(),
            'totalSectors' => Sector::count(),
            'recentCustodies' => CustodyLog::latest()->take(5)->get(),
        ])->layout('layouts.sgaiti');
    }
}
