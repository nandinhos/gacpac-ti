<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sector;

class SectorController extends Controller
{
    public function index()
    {
        return Sector::all();
    }

    public function store(Request $request)
    {
        return Sector::create($request->all());
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