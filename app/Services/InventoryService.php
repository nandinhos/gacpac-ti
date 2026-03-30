<?php

namespace App\Services;

use App\Models\InventoryRecord;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InventoryService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        return InventoryRecord::query()
            ->with(['sector', 'user'])
            ->when(isset($filters['sector_id']), fn ($q) => $q->where('sector_id', $filters['sector_id']))
            ->when(isset($filters['status']), fn ($q) => $q->where('status', $filters['status']))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): InventoryRecord
    {
        return InventoryRecord::create($data);
    }

    public function update(InventoryRecord $record, array $data): InventoryRecord
    {
        $record->update($data);

        return $record->fresh();
    }

    public function delete(InventoryRecord $record): void
    {
        $record->delete();
    }

    public function complete(InventoryRecord $record): InventoryRecord
    {
        $record->update(['status' => 'completed', 'completed_at' => now()]);

        return $record->fresh();
    }

    public function reopen(InventoryRecord $record, array $data = []): InventoryRecord
    {
        $record->update(['status' => 'open', 'completed_at' => null, ...$data]);

        return $record->fresh();
    }
}
