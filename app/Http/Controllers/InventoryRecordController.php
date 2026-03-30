<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryRecordResource;
use App\Models\InventoryRecord;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InventoryRecordController extends Controller
{
    public function __construct(private readonly InventoryService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('inventory.view');
        
        $records = $this->service->list($request->only(['sector_id', 'status', 'per_page']));
        return InventoryRecordResource::collection($records);
    }

    public function store(Request $request): InventoryRecordResource
    {
        $this->authorize('inventory.create');

        $validated = $request->validate([
            'sector_id' => ['required', 'exists:sectors,id'],
            'description' => ['required', 'string'],
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'open';

        return new InventoryRecordResource($this->service->create($validated));
    }

    public function show(InventoryRecord $inventoryRecord): InventoryRecordResource
    {
        $this->authorize('inventory.view');
        
        return new InventoryRecordResource($inventoryRecord->load(['sector', 'user']));
    }

    public function complete(InventoryRecord $inventoryRecord): InventoryRecordResource
    {
        $this->authorize('inventory.approve'); // Ou permissão específica para fechar
        
        return new InventoryRecordResource($this->service->complete($inventoryRecord));
    }

    public function reopen(InventoryRecord $inventoryRecord): InventoryRecordResource
    {
        $this->authorize('inventory.approve');
        
        return new InventoryRecordResource($this->service->reopen($inventoryRecord));
    }
}