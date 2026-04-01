<?php

namespace App\Http\Controllers;

use App\Http\Resources\MaintenanceRecordResource;
use App\Models\Asset;
use App\Models\MaintenanceRecord;
use App\Services\MaintenanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MaintenanceController extends Controller
{
    public function __construct(private readonly MaintenanceService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('maintenance.view');

        $records = $this->service->list($request->only(['asset_id', 'type', 'search', 'per_page']));

        return MaintenanceRecordResource::collection($records);
    }

    public function store(Request $request, Asset $asset): MaintenanceRecordResource
    {
        $this->authorize('maintenance.create');

        $validated = $request->validate([
            'type' => ['required', 'string'],
            'date' => ['required', 'date'],
            'description' => ['required', 'string'],
            'performed_by' => ['nullable', 'string'],
            'cost' => ['nullable', 'numeric'],
            'next_maintenance_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'is_upgrade' => ['boolean'],
            'parts_replaced' => ['nullable', 'string'],
        ]);

        $validated['asset_id'] = $asset->id;

        return new MaintenanceRecordResource($this->service->create($validated));
    }

    public function show(Asset $asset, MaintenanceRecord $maintenanceRecord): MaintenanceRecordResource
    {
        $this->authorize('maintenance.view');

        return new MaintenanceRecordResource($maintenanceRecord->load('asset'));
    }

    public function update(Request $request, Asset $asset, MaintenanceRecord $maintenanceRecord): MaintenanceRecordResource
    {
        $this->authorize('maintenance.edit');

        $validated = $request->validate([
            'type' => ['sometimes', 'string'],
            'date' => ['sometimes', 'date'],
            'description' => ['sometimes', 'string'],
            'performed_by' => ['nullable', 'string'],
            'cost' => ['nullable', 'numeric'],
            'next_maintenance_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'is_upgrade' => ['boolean'],
            'parts_replaced' => ['nullable', 'string'],
        ]);

        return new MaintenanceRecordResource($this->service->update($maintenanceRecord, $validated));
    }

    public function destroy(Asset $asset, MaintenanceRecord $maintenanceRecord): JsonResponse
    {
        $this->authorize('maintenance.delete');

        $this->service->delete($maintenanceRecord);

        return response()->json(['message' => 'Registro de manutenção removido com sucesso.']);
    }

    public function upcoming(Request $request): AnonymousResourceCollection
    {
        $this->authorize('maintenance.view');

        $records = $this->service->getUpcoming($request->get('days', 30));

        return MaintenanceRecordResource::collection($records);
    }
}
