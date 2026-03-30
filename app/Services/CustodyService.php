<?php

namespace App\Services;

use App\Models\CustodyLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustodyService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        return CustodyLog::query()
            ->with(['asset', 'user', 'sector'])
            ->when(isset($filters['sector_id']), fn ($q) => $q->where('sector_id', $filters['sector_id']))
            ->when(isset($filters['user_id']), fn ($q) => $q->where('user_id', $filters['user_id']))
            ->when(isset($filters['status']), fn ($q) => $q->where('status', $filters['status']))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): CustodyLog
    {
        return CustodyLog::create($data);
    }

    public function update(CustodyLog $custodyLog, array $data): CustodyLog
    {
        $custodyLog->update($data);

        return $custodyLog->fresh();
    }

    public function delete(CustodyLog $custodyLog): void
    {
        $custodyLog->delete();
    }

    public function checkin(CustodyLog $custodyLog): CustodyLog
    {
        $custodyLog->update(['checked_in_at' => now(), 'status' => 'returned']);

        return $custodyLog->fresh();
    }

    public function getNextNumber(): string
    {
        $last = CustodyLog::max('number') ?? 0;

        return str_pad($last + 1, 6, '0', STR_PAD_LEFT);
    }
}
