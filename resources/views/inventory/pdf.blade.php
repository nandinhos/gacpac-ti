<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Relatório de Inventário</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table th, .info-table td { border: 1px solid #ddd; padding: 5px; text-align: left; }
        .section-title { font-size: 14px; font-weight: bold; margin-top: 20px; margin-bottom: 10px; background-color: #f0f0f0; padding: 5px; }
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th, .items-table td { border: 1px solid #000; padding: 4px; }
        .items-table th { background-color: #eee; }
        .status-badge { padding: 2px 5px; border-radius: 3px; font-size: 10px; font-weight: bold; }
        .status-concluido { background-color: #d1fae5; color: #065f46; }
        .status-reaberto { background-color: #fef3c7; color: #92400e; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Relatório de Inventário: {{ $inventory->commission_number }}</h2>
        <p>Gerado em: {{ now()->format("d/m/Y H:i") }}</p>
    </div>

    <table class="info-table">
        <tr>
            <th>Setor</th>
            <td>{{ $inventory->sector->name ?? "Geral (Todos)" }}</td>
            <th>Status</th>
            <td>{{ $inventory->status }}</td>
        </tr>
        <tr>
            <th>Responsável</th>
            <td>{{ $inventory->responsibleUser->name ?? "N/A" }}</td>
            <th>Data Início</th>
            <td>{{ $inventory->start_date->format("d/m/Y") }}</td>
        </tr>
        <tr>
            <th>Data Fim</th>
            <td>{{ $inventory->end_date ? $inventory->end_date->format("d/m/Y") : "Em Andamento" }}</td>
            <th>Resumo</th>
            <td>
                Enc.: {{ $foundAssets->count() }} | 
                Pend.: {{ $pendingAssets->count() }} | 
                Extra: {{ $uncataloguedItems->count() }}
            </td>
        </tr>
    </table>

    @if($inventory->notes)
    <div class="section-title">Observações de Auditoria</div>
    <div style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;">
        {{ $inventory->notes }}
    </div>
    @endif

    <div class="section-title">Itens Conferidos ({{ $foundAssets->count() }})</div>
    <table class="items-table">
        <thead>
            <tr>
                <th>Patrimônio</th>
                <th>Nome</th>
                <th>Serial/QR</th>
                <th>Setor Origem</th>
            </tr>
        </thead>
        <tbody>
            @foreach($foundAssets as $asset)
            <tr>
                <td>{{ $asset->patrimony_number }}</td>
                <td>{{ $asset->name }}</td>
                <td>{{ $asset->qr_code ?? $asset->serial_number }}</td>
                <td>{{ $asset->sector->name ?? "N/A" }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($pendingAssets->count() > 0)
    <div class="section-title">Itens Pendentes ({{ $pendingAssets->count() }})</div>
    <table class="items-table">
        <thead>
            <tr>
                <th>Patrimônio</th>
                <th>Nome</th>
                <th>Serial/QR</th>
                <th>Setor Origem</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendingAssets as $asset)
            <tr>
                <td>{{ $asset->patrimony_number }}</td>
                <td>{{ $asset->name }}</td>
                <td>{{ $asset->qr_code ?? $asset->serial_number }}</td>
                <td>{{ $asset->sector->name ?? "N/A" }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($uncataloguedItems->count() > 0)
    <div class="section-title">Itens Não Catalogados ({{ $uncataloguedItems->count() }})</div>
    <table class="items-table">
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Data Encontrado</th>
                <th>Local</th>
            </tr>
        </thead>
        <tbody>
            @foreach($uncataloguedItems as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td>{{ $item->found_date->format("d/m/Y") }}</td>
                <td>{{ $item->location ?? "-" }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($inventory->reopenHistory->count() > 0)
    <div class="section-title">Histórico de Reaberturas</div>
    <table class="items-table">
        <thead>
            <tr>
                <th>Data</th>
                <th>Reaberto Por</th>
                <th>Justificativa</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventory->reopenHistory as $history)
            <tr>
                <td>{{ $history->reopened_at->format("d/m/Y H:i") }}</td>
                <td>{{ $history->reopenedBy->name ?? "N/A" }}</td>
                <td>{{ $history->justification }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div style="margin-top: 50px; text-align: center;">
        @if($inventory->is_commission && $inventory->commissionMembers->count() > 0)
            <p><strong>Comissão de Inventário</strong></p>
            <br>
            @foreach($inventory->commissionMembers as $member)
                <div style="margin-bottom: 40px;">
                    <p>____________________________________________________</p>
                    <p>{{ $member->rank }} {{ $member->name }}</p>
                </div>
            @endforeach
        @else
            <p>____________________________________________________</p>
            <p>Assinatura do Responsável</p>
        @endif
    </div>
</body>
</html>
