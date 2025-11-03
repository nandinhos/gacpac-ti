<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryRecord;
use App\Http\Requests\StoreInventoryRecordRequest;

class InventoryRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryRecord::query();
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('sectorId')) {
            $query->where('sector_id', $request->sectorId);
        }
        return $query->get();
    }

    public function store(StoreInventoryRecordRequest $request)
    {
        try {
            $inventory = InventoryRecord::create($request->validated());
            return response()->json([
                'message' => 'Inventário criado com sucesso',
                'data' => $inventory
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar inventário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(InventoryRecord $inventory)
    {
        return $inventory->load([
            'inventoryAssets.asset', 
            'uncataloguedItems',
            'responsibleUser',
            'sector'
        ]);
    }

    public function update(Request $request, InventoryRecord $inventory)
    {
        try {
            // Validar dados básicos
            $data = $request->validate([
                'status' => 'nullable|string|in:Em Andamento,Concluído,Reaberto',
                'notes' => 'nullable|string|max:2000',
                'end_date' => 'nullable|date',
                'found_items' => 'nullable|array',
                'found_items.*.asset_id' => 'required|integer|exists:assets,id',
                'found_items.*.observation' => 'nullable|string|max:500',
                'uncatalogued_items' => 'nullable|array',
                'uncatalogued_items.*' => 'string|max:255',
            ]);

            // Atualizar dados básicos do inventário
            $inventory->update([
                'status' => $data['status'] ?? $inventory->status,
                'notes' => $data['notes'] ?? $inventory->notes,
                'end_date' => $data['end_date'] ?? $inventory->end_date,
            ]);

            // Atualizar itens encontrados
            if (isset($data['found_items'])) {
                // Remover itens antigos
                $inventory->inventoryAssets()->delete();
                
                // Adicionar novos itens
                foreach ($data['found_items'] as $item) {
                    $inventory->inventoryAssets()->create([
                        'asset_id' => $item['asset_id'],
                        'observation' => $item['observation'] ?? null,
                        'found_at' => now(),
                    ]);
                }
            }

            // Atualizar itens não catalogados
            if (isset($data['uncatalogued_items'])) {
                // Remover itens antigos
                $inventory->uncataloguedItems()->delete();
                
                // Adicionar novos itens
                foreach ($data['uncatalogued_items'] as $description) {
                    $inventory->uncataloguedItems()->create([
                        'description' => $description,
                        'found_at' => now(),
                    ]);
                }
            }

            return response()->json([
                'message' => 'Inventário atualizado com sucesso',
                'data' => $inventory->load(['inventoryAssets', 'uncataloguedItems'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar inventário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(InventoryRecord $inventory)
    {
        $inventory->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function addFoundItem($id, Request $request)
    {
        $inventory = InventoryRecord::findOrFail($id);
        $inventory->inventoryAssets()->create($request->all());
        return $inventory->load('inventoryAssets');
    }

    public function addUncataloguedItem($id, Request $request)
    {
        $inventory = InventoryRecord::findOrFail($id);
        $inventory->uncataloguedItems()->create($request->all());
        return $inventory->load('uncataloguedItems');
    }

    public function complete($id, Request $request)
    {
        $inventory = InventoryRecord::findOrFail($id);
        $inventory->status = 'completed';
        $inventory->end_date = $request->input('endDate');
        $inventory->save();
        return $inventory;
    }

    public function reopen($id, Request $request)
    {
        $inventory = InventoryRecord::findOrFail($id);
        $inventory->status = 'Reaberto';
        $inventory->end_date = null; // Remover data de conclusão
        $inventory->save();
        return response()->json([
            'message' => 'Inventário reaberto com sucesso',
            'data' => $inventory
        ]);
    }

    public function deleteUncataloguedItem($id, $uncataloguedId)
    {
        $inventory = InventoryRecord::findOrFail($id);
        $inventory->uncataloguedItems()->where('id', $uncataloguedId)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}