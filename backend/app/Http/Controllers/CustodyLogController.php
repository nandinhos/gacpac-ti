<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustodyLog;

class CustodyLogController extends Controller
{
    public function index(Request $request)
    {
        $query = CustodyLog::query();
        if ($request->has('active')) {
            if ($request->boolean('active')) {
                $query->whereNull('checkin_date');
            } else {
                $query->whereNotNull('checkin_date');
            }
        }
        if ($request->has('userId')) {
            $query->where('user_id', $request->userId);
        }
        return $query->get();
    }

    public function store(Request $request)
    {
        $custody = CustodyLog::create($request->only(['cautela_number', 'user_id', 'checkout_date', 'term_url', 'signed_term_url', 'notes']));
        if ($request->has('assetIds')) {
            $custody->assets()->attach($request->assetIds);
        }
        return $custody;
    }

    public function show(CustodyLog $custody)
    {
        return $custody->load('assets');
    }

    public function update(Request $request, CustodyLog $custody)
    {
        $custody->update($request->all());
        return $custody;
    }

    public function destroy(CustodyLog $custody)
    {
        $custody->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function checkin($id, Request $request)
    {
        $custody = CustodyLog::findOrFail($id);
        $custody->checkin_date = $request->input('checkin_date');
        $custody->signed_term_url = $request->input('signed_term_url');
        $custody->save();
        return $custody;
    }

    public function getNextNumber()
    {
        $lastCustody = CustodyLog::orderBy('cautela_number', 'desc')->first();
        if ($lastCustody) {
            $lastNumber = (int) str_replace('/GAC-PAC/2024', '', $lastCustody->cautela_number);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        return ['nextCautelaNumber' => sprintf('%03d/GAC-PAC/2024', $nextNumber)];
    }
}