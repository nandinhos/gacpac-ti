<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\User; // Added this use statement
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function assets(Request $request)
    {
        $query = Asset::query()->with(['category', 'location']);

        // Filtros (implementação básica inicial)
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Ordenação padrão
        $assets = $query->orderBy('name')->get();

        $pdf = Pdf::loadView('reports.pdf.assets', [
            'assets' => $assets,
            'title' => 'Relatório Geral de Ativos',
            'filters' => $request->all()
        ]);

        return $pdf->stream('relatorio-ativos-' . now()->format('YmdHis') . '.pdf');
    }

    public function maintenance(Request $request)
    {
        // TODO: Implementar model MaintenanceRecord se não existir ou usar o relacionamento correto
        // Assumindo que MaintenanceRecord existe e tem relacionamento com Asset e User
        $query = \App\Models\MaintenanceRecord::query()->with(['asset']);

        if ($request->has('asset_id') && $request->asset_id) {
            $query->where('asset_id', $request->asset_id);
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $records = $query->orderBy('date', 'desc')->get();

        $pdf = Pdf::loadView('reports.pdf.maintenance', [
            'records' => $records,
            'title' => 'Relatório de Manutenção',
            'filters' => $request->all()
        ]);

        return $pdf->stream('relatorio-manutencao-' . now()->format('YmdHis') . '.pdf');
    }

    public function term(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::find($request->user_id);
        
        // Buscar ativos sob a guarda deste usuário
        // Assumindo que Asset tem custodian_user_id
        $assets = Asset::where('custodian_user_id', $user->id)
            ->with(['category', 'location'])
            ->orderBy('category_id') // Agrupar por categoria é útil
            ->orderBy('name')
            ->get();

        $pdf = Pdf::loadView('reports.pdf.term', [
            'user' => $user,
            'assets' => $assets,
            'title' => 'Termo de Responsabilidade',
            'date' => now()
        ]);

        return $pdf->stream('termo-responsabilidade-' . Str::slug($user->name) . '-' . now()->format('Ymd') . '.pdf');
    }
}
