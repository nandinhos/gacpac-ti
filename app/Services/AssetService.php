<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AssetService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        return Asset::query()
            ->with(['category', 'sector'])
            ->when(isset($filters['sector_id']), fn ($q) => $q->where('sector_id', $filters['sector_id']))
            ->when(isset($filters['category_id']), fn ($q) => $q->where('category_id', $filters['category_id']))
            ->when(isset($filters['search']), function ($q) use ($filters) {
                $driver = $q->getConnection()->getDriverName();
                $operator = $driver === 'sqlite' ? 'like' : 'ilike';
                return $q->where('name', $operator, "%{$filters['search']}%");
            })
            ->paginate($filters['per_page'] ?? 15);
    }

    public function findByQrCode(string $qrCode): Asset
    {
        return Asset::where('qr_code', $qrCode)->firstOrFail();
    }

    public function create(array $data): Asset
    {
        return Asset::create($data);
    }

    public function update(Asset $asset, array $data): Asset
    {
        $asset->update($data);

        return $asset->fresh();
    }

    public function delete(Asset $asset): void
    {
        $asset->delete();
    }

    public function getNextQrCode(): string
    {
        $last = Asset::orderByDesc('qr_code')->value('qr_code');

        if (! $last) {
            return 'QR-00001';
        }

        $number = (int) str_replace('QR-', '', $last);

        return 'QR-'.str_pad($number + 1, 5, '0', STR_PAD_LEFT);
    }
}
