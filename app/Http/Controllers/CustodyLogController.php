<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\StoreCustodyLogRequest;
use App\Http\Requests\UpdateCustodyLogRequest;
use App\Http\Requests\CheckinCustodyLogRequest;
use App\Models\CustodyLog;
use App\Models\User; // Replaced MilitaryUser with User
use App\Models\Asset; // Added Asset model
use App\Notifications\CustodyCreatedNotification;
use Barryvdh\DomPDF\Facade\Pdf; // Added Pdf facade
use Illuminate\Support\Facades\DB; // Added DB facade

class CustodyLogController extends Controller
{
    public function index(Request $request)
    {
        $query = CustodyLog::with(['user', 'assets']);
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

    public function store(StoreCustodyLogRequest $request)
    {
        $validated = $request->validated();

        $assets = \App\Models\Asset::whereIn('id', $validated['assetIds'])->get();
        foreach ($assets as $asset) {
            if ($asset->status !== 'Disponível') {
                return response()->json([
                    'message' => "Ativo {$asset->qr_code} não está disponível (status: {$asset->status})",
                    'errors' => ['assetIds' => ["Ativo {$asset->qr_code} não está disponível."]]
                ], 422);
            }
        }

        try {
            $custody = \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
                $custodyData = collect($validated)->only((new CustodyLog)->getFillable())->toArray();
                $custody = CustodyLog::create($custodyData);

                $custody->assets()->attach($validated['assetIds']);

                // Update asset statuses
                \App\Models\Asset::whereIn('id', $validated['assetIds'])->update([
                    'status' => 'Em Uso',
                    'custodian_user_id' => $validated['user_id']
                ]);

                return $custody;
            });

            // Notification
            $adminUsers = User::whereIn('user_role', ['admin', 'commission'])->get();
            Notification::send($adminUsers, new CustodyCreatedNotification($custody));

            return response()->json([
                'message' => 'Cautela criada com sucesso',
                'data' => $custody->load('assets', 'user')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar cautela.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(CustodyLog $custody)
    {
        return $custody->load('assets');
    }

    public function update(UpdateCustodyLogRequest $request, CustodyLog $custody)
    {
        $validatedData = collect($request->validated())->only((new CustodyLog)->getFillable())->toArray();
        $custody->update($validatedData);
        return $custody->load(['user', 'assets']);
    }

    public function destroy(CustodyLog $custody)
    {
        $custody->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function checkin(CheckinCustodyLogRequest $request, CustodyLog $custody)
    {
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $custody) {
                $validatedData = $request->validated();

                $custody->update([
                    'checkin_date' => $validatedData['checkin_date'],
                    'signed_term_url' => $validatedData['signed_term_url'] ?? $custody->signed_term_url,
                ]);

                // Update asset statuses
                foreach ($custody->assets as $asset) {
                    $asset->update([
                        'status' => 'Disponível',
                        'custodian_user_id' => null
                    ]);
                }
            });

            return response()->json([
                'message' => 'Cautela devolvida com sucesso',
                'data' => $custody->load('assets', 'user')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao devolver cautela.',
                'error' => $e->getMessage()
            ], 500);
        }
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

    public function reports(Request $request)
    {
        $type = $request->get('type', 'active'); // active, completed, all, user

        $query = CustodyLog::with(['user', 'assets']);

        switch ($type) {
            case 'active':
                $query->whereNull('checkin_date');
                break;
            case 'completed':
                $query->whereNotNull('checkin_date');
                break;
            case 'user':
                if ($request->has('user_id')) {
                    $query->where('user_id', $request->user_id);
                }
                break;
        }

        if ($request->has('start_date')) {
            $query->where('checkout_date', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('checkout_date', '<=', $request->end_date);
        }

        $custodies = $query->orderBy('checkout_date', 'desc')->get();

        $summary = [
            'total_custodies' => $custodies->count(),
            'active_custodies' => $custodies->whereNull('checkin_date')->count(),
            'completed_custodies' => $custodies->whereNotNull('checkin_date')->count(),
            'total_assets' => $custodies->sum(function ($custody) {
                return $custody->assets->count();
            })
        ];

        return response()->json([
            'summary' => $summary,
            'custodies' => $custodies
        ]);
    }
}