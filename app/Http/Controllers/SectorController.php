<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSectorRequest;
use App\Http\Requests\UpdateSectorRequest;
use App\Models\Sector;
use App\Services\SectorService;
use Illuminate\Support\Facades\Cache;

class SectorController extends Controller
{
    public function __construct(private SectorService $service) {}

    public function index()
    {
        return Cache::remember('sectors_list', 3600, function () { // Cache por 1 hora
            return $this->service->all();
        });
    }

    public function store(StoreSectorRequest $request)
    {
        try {
            $sector = $this->service->create($request->validated());

            // Limpar cache de setores
            Cache::forget('sectors_list');

            return response()->json([
                'message' => 'Setor criado com sucesso',
                'data' => $sector,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar setor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Sector $sector)
    {
        return $sector;
    }

    public function update(UpdateSectorRequest $request, Sector $sector)
    {
        $sector = $this->service->update($sector, $request->validated());

        // Limpar cache de setores
        Cache::forget('sectors_list');

        return $sector;
    }

    public function destroy(Sector $sector)
    {
        $this->service->delete($sector);

        // Limpar cache de setores
        Cache::forget('sectors_list');

        return response()->json(['message' => 'Deleted']);
    }
}
