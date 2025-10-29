<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MilitaryUser;

class MilitaryUserController extends Controller
{
    public function index(Request $request)
    {
        $query = MilitaryUser::query();
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }
        if ($request->has('sectorId')) {
            $query->where('sector_id', $request->sectorId);
        }
        return $query->get();
    }

    public function store(Request $request)
    {
        return MilitaryUser::create($request->all());
    }

    public function show(MilitaryUser $user)
    {
        return $user;
    }

    public function update(Request $request, MilitaryUser $user)
    {
        $user->update($request->all());
        return $user;
    }

    public function destroy(MilitaryUser $user)
    {
        $user->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function getActiveUsers()
    {
        return MilitaryUser::where('is_active', true)->get();
    }

    public function getUsersBySector($sectorId)
    {
        return MilitaryUser::where('sector_id', $sectorId)->get();
    }
}