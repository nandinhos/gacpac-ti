<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        return User::query()
            ->with('sector')
            ->when(isset($filters['sector_id']), fn ($q) => $q->where('sector_id', $filters['sector_id']))
            ->when(isset($filters['search']), fn ($q) => $q->where('name', 'ilike', "%{$filters['search']}%"))
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return $user->fresh();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function getActive(): Collection
    {
        return User::query()
            ->with('sector')
            ->whereNotNull('email_verified_at')
            ->orderBy('name')
            ->get();
    }

    public function getBySector(int $sectorId): Collection
    {
        return User::where('sector_id', $sectorId)->orderBy('name')->get();
    }
}
