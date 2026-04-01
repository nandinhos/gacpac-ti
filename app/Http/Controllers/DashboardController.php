<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\CustodyLog;
use App\Models\InventoryRecord;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Limpar cache do dashboard quando dados são modificados
     */
    private function clearDashboardCache()
    {
        Cache::forget('dashboard_stats');
    }

    public function getStats()
    {
        return Cache::remember('dashboard_stats', 300, function () { // Cache por 5 minutos
            $assets = Asset::all();
            $users = User::all();
            $sectors = Sector::all();
            $custodyLogs = CustodyLog::all();
            $inventoryRecords = InventoryRecord::all();

            $totalAssets = $assets->count();
            $assetsByStatus = $assets->groupBy('status')->map->count();
            $assetsByCategory = $assets->groupBy('category')->map->count();
            $maintenanceNeeded = $assets->where('status', 'Manutenção')->count();

            $activeCustody = $custodyLogs->whereNull('checkin_date')->count();

            $activeInventory = $inventoryRecords->where('status', 'Em Andamento')->count();

            $totalUsers = $users->count();
            $activeUsers = $users->where('is_active', true)->count();

            $totalSectors = $sectors->count();

            $recentAssets = $assets->sortByDesc('created_at')->take(5)->map(function ($asset) {
                return [
                    'id' => $asset->id,
                    'name' => $asset->name,
                    'qr_code' => $asset->qr_code,
                    'category' => $asset->category,
                    'created_at' => $asset->created_at->toISOString(),
                ];
            });

            $recentCustody = $custodyLogs->sortByDesc('checkout_date')->take(5)->map(function ($custody) {
                return [
                    'id' => $custody->id,
                    'cautela_number' => $custody->cautela_number,
                    'checkout_date' => $custody->checkout_date->toISOString(),
                    'checkin_date' => $custody->checkin_date?->toISOString(),
                    'user_name' => '',
                    'user_rank' => '',
                ];
            });

            return response()->json([
                'assets' => [
                    'total' => $totalAssets,
                    'byStatus' => [
                        'emUso' => $assetsByStatus->get('Em Uso', 0),
                        'disponivel' => $assetsByStatus->get('Disponível', 0),
                        'manutencao' => $assetsByStatus->get('Manutenção', 0),
                        'baixado' => $assetsByStatus->get('Baixado', 0),
                    ],
                    'byCategory' => $assetsByCategory->toArray(),
                    'maintenanceNeeded' => $maintenanceNeeded,
                ],
                'custody' => [
                    'active' => $activeCustody,
                ],
                'inventory' => [
                    'active' => $activeInventory,
                ],
                'users' => [
                    'total' => $totalUsers,
                    'active' => $activeUsers,
                ],
                'sectors' => [
                    'total' => $totalSectors,
                ],
                'recent' => [
                    'assets' => $recentAssets->values(),
                    'custody' => $recentCustody->values(),
                ],
            ]);
        });
    }
}
