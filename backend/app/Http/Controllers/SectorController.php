<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sector;
use App\Http\Requests\StoreSectorRequest;
use App\Http\Requests\UpdateSectorRequest;

class SectorController extends Controller
{
    public function index()
    {
        return Sector::all();
    }

    public function store(StoreSectorRequest $request)
    {
        try {
            $sector = Sector::create($request->validated());
            return response()->json([
                'message' => 'Setor criado com sucesso',
                'data' => $sector
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar setor',
                'error' => $e->getMessage()
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
        return $sector;
    }

    public function destroy(Sector $sector)
    {
        $sector->delete();
        return response()->json(['message' => 'Deleted']);
    }
}