<?php

namespace App\Services;

use App\Models\Sector;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SectorService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        return Sector::query()
            ->when(isset($filters['search']), fn ($q) => $q->where('name', 'ilike', "%{$filters['search']}%"))
            ->orderBy('name')
            ->paginate($filters['per_page'] ?? 15);
    }

    public function all(): Collection
    {
        return Sector::orderBy('name')->get();
    }

    public function create(array $data): Sector
    {
        return Sector::create($data);
    }

    public function update(Sector $sector, array $data): Sector
    {
        $sector->update($data);

        return $sector->fresh();
    }

    public function delete(Sector $sector): void
    {
        $sector->delete();
    }
}
