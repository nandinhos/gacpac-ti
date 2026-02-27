@extends('reports.pdf.layout')

@section('content')
    <div class="summary-box">
        <strong>Filtros Aplicados:</strong>
        @if(empty($filters['category_id']) && empty($filters['status']))
            Nenhum (Todos os ativos)
        @else
            {{-- TODO: Melhorar exibição dos filtros com nomes amigáveis --}}
            @if(!empty($filters['category_id'])) Categoria: {{ $filters['category_id'] }} @endif
            @if(!empty($filters['status'])) Status: {{ $filters['status'] }} @endif
        @endif
        <br>
        <strong>Total de Registros:</strong> {{ $assets->count() }}
        <br>
        <strong>Valor Total:</strong> R$ {{ number_format($assets->sum('cost'), 2, ',', '.') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%">Tombo</th>
                <th style="width: 30%">Ativo / Descrição</th>
                <th style="width: 15%">Categoria</th>
                <th style="width: 15%">Localização</th>
                <th style="width: 10%">Status</th>
                <th style="width: 10%">Aquisição</th>
                <th style="width: 10%; text-align: right;">Valor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $asset)
                <tr>
                    <td>{{ $asset->code }}</td>
                    <td>
                        <strong>{{ $asset->name }}</strong>
                        <br>
                        <span style="font-size: 8pt; color: #666;">NS: {{ $asset->serial_number ?? 'N/A' }}</span>
                    </td>
                    <td>{{ $asset->category->name ?? '-' }}</td>
                    <td>{{ $asset->location->name ?? '-' }}</td>
                    <td>
                        @php
                            $badgeClass = match($asset->status) {
                                'active' => 'badge-green',
                                'maintenance' => 'badge-red',
                                'archived' => 'badge-gray',
                                default => 'badge-blue'
                            };
                            $statusLabel = match($asset->status) {
                                'active' => 'Ativo',
                                'maintenance' => 'Manutenção',
                                'archived' => 'Arquivado',
                                default => $asset->status
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td>{{ $asset->acquisition_date ? \Carbon\Carbon::parse($asset->acquisition_date)->format('d/m/Y') : '-' }}</td>
                    <td style="text-align: right;">{{ $asset->cost ? 'R$ ' . number_format($asset->cost, 2, ',', '.') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">Nenhum ativo encontrado para os filtros selecionados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
