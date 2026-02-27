<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Asset;
use App\Models\CustodyLog;
use App\Models\MaintenanceRecord;
use App\Models\User;
use App\Models\Sector;

class Dashboard extends Component
{
    public function render()
    {
        $overdueMaintenances = MaintenanceRecord::overdue()
            ->with('asset')
            ->orderBy('next_maintenance_date')
            ->get();

        $upcomingMaintenances = MaintenanceRecord::upcoming(30)
            ->with('asset')
            ->orderBy('next_maintenance_date')
            ->get();

        return view('livewire.dashboard', [
            'totalAssets' => Asset::count(),
            'assetsInUse' => Asset::where('status', 'EM_USO')->count(),
            'activeCustodies' => CustodyLog::open()->count(),
            'totalMilUsers' => User::count(), // Changed from MilitaryUser::count()
            'totalSectors' => Sector::count(),
            'recentCustodies' => CustodyLog::latest()->take(5)->get(),
            'overdueMaintenances' => $overdueMaintenances,
            'upcomingMaintenances' => $upcomingMaintenances,
        ])->layout('layouts.sgaiti');
    }
}
