<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSectorRequest;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SectorController extends Controller
{
    public function index()
    {
        return Cache::remember('sectors_list', 3600, function () { // Cache por 1 hora
            return Sector::orderBy('name')->get();
        });
    }

    public function store(StoreSectorRequest $request)
    {
        try {
            $sector = Sector::create($request->validated());

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

    public function update(Request $request, Sector $sector)
    {
        $sector->update($request->all());

        // Limpar cache de setores
        Cache::forget('sectors_list');

        return $sector;
    }

    public function destroy(Sector $sector)
    {
        $sector->delete();

        // Limpar cache de setores
        Cache::forget('sectors_list');

        return response()->json(['message' => 'Deleted']);
    }
}
