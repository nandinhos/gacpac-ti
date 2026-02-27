@extends('reports.pdf.layout')

@section('content')
    <div style="text-align: justify; margin-bottom: 20px;">
        <h2 style="text-align: center; font-size: 14pt; margin-bottom: 20px;">TERMO DE RESPONSABILIDADE E CAUTELA DE ATIVOS</h2>

        <p>
            Pelo presente instrumento, eu, <strong>{{ $user->name }}</strong>, 
            {{ $user->rank ? $user->rank . ' - ' : '' }}
            {{-- Assumindo campos rank/post/register se existirem --}}
            declaro ter recebido sob minha guarda e responsabilidade os bens patrimoniais abaixo relacionados, 
            comprometendo-me a zelar pela sua conservação e a utilizá-los exclusivamente para fins de serviço.
        </p>
        <p>
            Declaro ainda estar ciente de que deverei comunicar imediatamente qualquer alteração, dano ou extravio dos referidos bens ao setor responsável.
        </p>
    </div>

    <div class="summary-box">
        <strong>Total de Itens:</strong> {{ $assets->count() }}
        <br>
        <strong>Valor Total sob Custódia:</strong> R$ {{ number_format($assets->sum('cost'), 2, ',', '.') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%">Tombo</th>
                <th style="width: 35%">Descrição do Ativo</th>
                <th style="width: 20%">Nº Série</th>
                <th style="width: 15%">Estado</th>
                <th style="width: 15%; text-align: right;">Valor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $asset)
                <tr>
                    <td>{{ $asset->code }}</td>
                    <td>
                        <strong>{{ $asset->name }}</strong>
                        <br>
                        <span style="font-size: 8pt; color: #666;">{{ $asset->category->name ?? '-' }}</span>
                    </td>
                    <td>{{ $asset->serial_number ?? '-' }}</td>
                    <td>{{ ucfirst($asset->condition ?? 'Bom') }}</td>
                    <td style="text-align: right;">{{ $asset->cost ? 'R$ ' . number_format($asset->cost, 2, ',', '.') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Nenhum ativo vinculado a este responsável.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 50px;">
        <p style="text-align: right; margin-bottom: 50px;">
            {{ config('app.city', 'Manaus') }}, {{ \Carbon\Carbon::now()->isoFormat('D \d\e MMMM \d\e Y') }}.
        </p>

        <table style="border: none; margin-top: 30px;">
            <tr style="background: none;">
                <td style="border: none; text-align: center; width: 50%;">
                    <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto; padding-top: 5px;">
                        <strong>{{ $user->name }}</strong><br>
                        <span style="font-size: 8pt;">Responsável / Recebedor</span>
                    </div>
                </td>
                <td style="border: none; text-align: center; width: 50%;">
                    <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto; padding-top: 5px;">
                        <strong>{{ auth()->user()->name ?? 'Chefe do Setor' }}</strong><br>
                        <span style="font-size: 8pt;">Conferente / Setor de TI</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
@endsection
