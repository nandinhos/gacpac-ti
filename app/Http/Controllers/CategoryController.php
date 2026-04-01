<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoryService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $categories = $this->service->list($request->only(['search', 'per_page']));

        return CategoryResource::collection($categories);
    }

    public function store(Request $request): CategoryResource
    {
        $this->authorize('assets.create');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
        ]);

        return new CategoryResource($this->service->create($validated));
    }

    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    public function update(Request $request, Category $category): CategoryResource
    {
        $this->authorize('assets.edit');

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255', 'unique:categories,name,'.$category->id],
            'description' => ['nullable', 'string'],
        ]);

        return new CategoryResource($this->service->update($category, $validated));
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->authorize('assets.delete');

        $this->service->delete($category);

        return response()->json(['message' => 'Categoria removida com sucesso.']);
    }
}
