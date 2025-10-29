<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryRecord;

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

    public function store(Request $request)
    {
        return InventoryRecord::create($request->all());
    }

    public function show(InventoryRecord $inventory)
    {
        return $inventory;
    }

    public function update(Request $request, InventoryRecord $inventory)
    {
        $inventory->update($request->all());
        return $inventory;
    }

    public function destroy(InventoryRecord $inventory)
    {
        $inventory->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function addFoundItem($id, Request $request)
    {
        $inventory = InventoryRecord::findOrFail($id);
        $inventory->foundItems()->create($request->all());
        return $inventory->load('foundItems');
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
        $inventory->status = 'reopened';
        $inventory->reopenHistory()->create([
            'justification' => $request->input('justification'),
            'reopened_by' => $request->input('userId'),
            'reopened_at' => now()
        ]);
        $inventory->save();
        return $inventory;
    }

    public function deleteUncataloguedItem($id, $uncataloguedId)
    {
        $inventory = InventoryRecord::findOrFail($id);
        $inventory->uncataloguedItems()->where('id', $uncataloguedId)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}