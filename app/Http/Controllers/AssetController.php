<?php

namespace App\Http\Controllers;

use App\Http\Resources\AssetResource;
use App\Models\Asset;
use App\Services\AssetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AssetController extends Controller
{
    public function __construct(private readonly AssetService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('assets.view');
        
        $assets = $this->service->list($request->only(['category_id', 'sector_id', 'search', 'per_page']));
        return AssetResource::collection($assets);
    }

    public function store(Request $request): AssetResource
    {
        $this->authorize('assets.create');

        $validated = $request->validate([
            'qr_code' => ['required', 'string', 'unique:assets,qr_code'],
            'name' => ['required', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'unique:assets,serial_number'],
            'category_id' => ['required', 'exists:categories,id'],
            'sector_id' => ['required', 'exists:sectors,id'],
            'status' => ['required', 'string'],
        ]);

        $validated['category'] = \App\Models\Category::find($validated['category_id'])->name;

        return new AssetResource($this->service->create($validated));
    }

    public function show(Asset $asset): AssetResource
    {
        $this->authorize('assets.view');
        
        return new AssetResource($asset->load(['category', 'sector']));
    }

    public function update(Request $request, Asset $asset): AssetResource
    {
        $this->authorize('assets.edit');

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'unique:assets,serial_number,' . $asset->id],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'sector_id' => ['sometimes', 'exists:sectors,id'],
            'status' => ['sometimes', 'string'],
        ]);

        return new AssetResource($this->service->update($asset, $validated));
    }

    public function destroy(Asset $asset): JsonResponse
    {
        $this->authorize('assets.delete');
        
        $this->service->delete($asset);
        return response()->json(['message' => 'Ativo removido com sucesso.']);
    }

    public function getByQrCode(string $qrCode): AssetResource
    {
        $this->authorize('assets.view');
        
        return new AssetResource($this->service->findByQrCode($qrCode));
    }

    public function nextQrCode(): JsonResponse
    {
        $this->authorize('assets.create');
        
        return response()->json(['qr_code' => $this->service->getNextQrCode()]);
    }
}