@extends('reports.pdf.layout')

@section('content')
    <div class="summary-box">
        <strong>Filtros Aplicados:</strong>
        @if(empty($filters['asset_id']) && empty($filters['type']) && empty($filters['start_date']) && empty($filters['end_date']))
            Nenhum (Todas as manutenções)
        @else
            @if(!empty($filters['type'])) Tipo: {{ ucfirst($filters['type']) }} @endif
            @if(!empty($filters['start_date'])) De: {{ \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') }} @endif
            @if(!empty($filters['end_date'])) Até: {{ \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y') }} @endif
        @endif
        <br>
        <strong>Total de Registros:</strong> {{ $records->count() }}
        <br>
        <strong>Custo Total:</strong> R$ {{ number_format($records->sum('cost'), 2, ',', '.') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%">Data</th>
                <th style="width: 25%">Ativo</th>
                <th style="width: 15%">Tipo</th>
                <th style="width: 25%">Descrição / Técnico</th>
                <th style="width: 20%; text-align: right;">Custo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}</td>
                    <td>
                        {{ $record->asset->name ?? 'N/A' }}
                        <br>
                        <span style="font-size: 8pt; color: #666;">{{ $record->asset->code ?? '' }}</span>
                    </td>
                    <td>
                        @php
                            $badgeClass = match($record->type) {
                                'preventive' => 'badge-blue',
                                'corrective' => 'badge-red',
                                default => 'badge-gray'
                            };
                            $typeLabel = match($record->type) {
                                'preventive' => 'Preventiva',
                                'corrective' => 'Corretiva',
                                default => ucfirst($record->type)
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $typeLabel }}</span>
                    </td>
                    <td>
                        {{ Str::limit($record->description, 50) }}
                        <br>
                        <span style="font-size: 8pt; color: #666;">Téc: {{ $record->performed_by ?? 'Externo' }}</span>
                    </td>
                    <td style="text-align: right;">{{ $record->cost ? 'R$ ' . number_format($record->cost, 2, ',', '.') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Nenhum registro de manutenção encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
