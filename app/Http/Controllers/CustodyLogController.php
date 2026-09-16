<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustodyLogRequest;
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

    public function store(StoreCustodyLogRequest $request): CustodyLogResource
    {
        $this->authorize('assets.edit');

        $log = $this->service->create($request->only([
            'cautela_number', 'user_id', 'checkout_date', 'term_url', 'notes',
        ]));

        $log->assets()->attach($request->validated()['assetIds']);

        return new CustodyLogResource($log->load(['assets', 'user']));
    }

    public function show(CustodyLog $custodyLog): CustodyLogResource
    {
        $this->authorize('assets.view');

        return new CustodyLogResource($custodyLog->load(['assets', 'user']));
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
