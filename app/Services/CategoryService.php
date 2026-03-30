<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        return Category::query()
            ->when(isset($filters['search']), fn ($q) => $q->where('name', 'ilike', "%{$filters['search']}%"))
            ->orderBy('name')
            ->paginate($filters['per_page'] ?? 15);
    }

    public function all(): Collection
    {
        return Category::orderBy('name')->get();
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);

        return $category->fresh();
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
