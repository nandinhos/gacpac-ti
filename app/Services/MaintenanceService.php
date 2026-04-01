<?php

namespace App\Services;

use App\Models\MaintenanceRecord;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MaintenanceService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        return MaintenanceRecord::query()
            ->with('asset')
            ->when(isset($filters['asset_id']), fn ($q) => $q->where('asset_id', $filters['asset_id']))
            ->when(isset($filters['type']), fn ($q) => $q->where('type', $filters['type']))
            ->when(isset($filters['search']), function ($q) use ($filters) {
                $q->where('description', 'ilike', "%{$filters['search']}%")
                    ->orWhere('performed_by', 'ilike', "%{$filters['search']}%");
            })
            ->latest('date')
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): MaintenanceRecord
    {
        return MaintenanceRecord::create($data);
    }

    public function update(MaintenanceRecord $record, array $data): MaintenanceRecord
    {
        $record->update($data);

        return $record->fresh();
    }

    public function delete(MaintenanceRecord $record): void
    {
        $record->delete();
    }

    public function getUpcoming(int $days = 30)
    {
        return MaintenanceRecord::upcoming($days)->with('asset')->get();
    }
}
