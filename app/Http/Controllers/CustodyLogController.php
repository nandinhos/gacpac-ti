<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustodyLogResource;
use App\Models\CustodyLog;
use App\Services\CustodyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustodyLogController extends Controller
{
    public function __construct(private readonly CustodyService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('assets.view'); // Usando permissão de ativos para cautelas por enquanto ou role auditor
        
        $logs = $this->service->list($request->only(['sector_id', 'user_id', 'status', 'per_page']));
        return CustodyLogResource::collection($logs);
    }

    public function store(Request $request): CustodyLogResource
    {
        $this->authorize('assets.edit');

        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'user_id' => ['required', 'exists:users,id'],
            'sector_id' => ['required', 'exists:sectors,id'],
            'date' => ['required', 'date'],
            'return_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['number'] = $this->service->getNextNumber();
        $validated['status'] = 'active';

        return new CustodyLogResource($this->service->create($validated));
    }

    public function show(CustodyLog $custodyLog): CustodyLogResource
    {
        $this->authorize('assets.view');
        
        return new CustodyLogResource($custodyLog->load(['asset', 'user', 'sector']));
    }

    public function checkin(CustodyLog $custodyLog): CustodyLogResource
    {
        $this->authorize('assets.edit');
        
        return new CustodyLogResource($this->service->checkin($custodyLog));
    }

    public function nextNumber(): JsonResponse
    {
        return response()->json(['number' => $this->service->getNextNumber()]);
    }
}