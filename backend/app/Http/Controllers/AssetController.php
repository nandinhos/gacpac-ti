<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::query();
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('sectorId')) {
            $query->where('sector_id', $request->sectorId);
        }
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function () use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('serial_number', 'like', "%{$search}%")
                      ->orWhere('patrimony_id', 'like', "%{$search}%");
            });
        }
        return $query->get();
    }

    public function store(Request $request)
    {
        return Asset::create($request->all());
    }

    public function show(Asset $asset)
    {
        return $asset;
    }

    public function update(Request $request, Asset $asset)
    {
        $asset->update($request->all());
        return $asset;
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return response()->json(['message' => 'Deleted']);
    }
}