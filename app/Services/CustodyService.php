<?php

namespace App\Services;

use App\Models\CustodyLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustodyService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        return CustodyLog::query()
            ->with(['assets', 'user'])
            ->when(isset($filters['sector_id']), fn ($q) => $q->whereHas(
                'user', fn ($u) => $u->where('sector_id', $filters['sector_id'])
            ))
            ->when(isset($filters['user_id']), fn ($q) => $q->where('user_id', $filters['user_id']))
            ->when(($filters['status'] ?? null) === 'open', fn ($q) => $q->open())
            ->when(($filters['status'] ?? null) === 'closed', fn ($q) => $q->closed())
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
        $custodyLog->update(['checkin_date' => now()]);

        return $custodyLog->fresh();
    }

    public function getNextNumber(): string
    {
        $year = now()->year;

        $last = CustodyLog::where('cautela_number', 'like', '%/'.$year)
            ->orderBy('id', 'desc')
            ->value('cautela_number');

        $next = $last && preg_match('/^(\d+)/', $last, $m) ? ((int) $m[1]) + 1 : 1;

        return str_pad($next, 3, '0', STR_PAD_LEFT).'/GAC-PAC/'.$year;
    }
}
