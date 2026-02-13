<div>
    {{-- Botões de Ação (apenas na tela) --}}
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            <svg style="width: 18px; height: 18px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Imprimir
        </button>
        <button onclick="window.close()" class="btn-back">
            <svg style="width: 16px; height: 16px; margin-right: 6px; transform: translateY(-1px);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Fechar
        </button>
    </div>

    <div class="print-container">
        {{-- Header Oficial --}}
        <div class="text-center border-b mb-4" style="padding-bottom: 15px;">
            <div class="text-xl font-bold mb-2">COMANDO DA AERONÁUTICA</div>
            <div class="text-lg font-bold mb-2">GRUPAMENTO DE APOIO CAMPO GRANDE</div>
            <div class="mb-2">SEÇÃO DE TECNOLOGIA DA INFORMAÇÃO</div>
            <div class="text-sm" style="color: #666;">Sistema de Gestão de Ativos de TI - SGAITI</div>
        </div>

        {{-- Título do Documento --}}
        <div class="text-xl font-bold text-center mb-4" style="text-transform: uppercase; margin: 20px 0;">
            TERMO DE RESPONSABILIDADE - CAUTELA DE MATERIAL
        </div>

        {{-- Informações da Cautela --}}
        <table style="margin-bottom: 20px;">
            <tbody>
                <tr>
                    <td style="font-weight: bold; width: 30%;">Nº da Cautela:</td>
                    <td class="text-lg font-bold">{{ $custodyLog->cautela_number }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Data de Abertura:</td>
                    <td>{{ $custodyLog->checkout_date ? $custodyLog->checkout_date->format('d/m/Y') : '-' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Status:</td>
                    <td style="font-weight: bold; color: {{ $custodyLog->checkin_date ? '#d97706' : '#059669' }};">
                        {{ $custodyLog->checkin_date ? 'CONCLUÍDA' : 'ATIVA' }}
                    </td>
                </tr>
                @if($custodyLog->checkin_date)
                <tr>
                    <td style="font-weight: bold;">Data de Devolução:</td>
                    <td>{{ $custodyLog->checkin_date->format('d/m/Y') }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        {{-- Dados do Responsável --}}
        <div class="font-bold text-lg border-b mb-2" style="text-transform: uppercase; padding-bottom: 5px;">
            Dados do Militar Responsável
        </div>
        <table style="margin-bottom: 20px;">
            <tbody>
                <tr>
                    <td style="font-weight: bold; width: 25%;">Nome Completo:</td>
                    <td class="font-bold">{{ $custodyLog->user->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Posto/Graduação:</td>
                    <td>{{ $custodyLog->user->rank ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">ID Militar:</td>
                    <td>{{ $custodyLog->user->military_id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Setor:</td>
                    <td>{{ $custodyLog->user->sector->name ?? 'N/A' }}</td>
                </tr>
                @if($custodyLog->user->email ?? false)
                <tr>
                    <td style="font-weight: bold;">E-mail:</td>
                    <td>{{ $custodyLog->user->email }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        {{-- Lista de Ativos --}}
        <div class="font-bold text-lg border-b mb-2" style="text-transform: uppercase; padding-bottom: 5px;">
            Relação de Material Sob Cautela
        </div>
        <table style="margin-bottom: 30px;">
            <thead>
                <tr class="bg-gray">
                    <th style="width: 8%;">Item</th>
                    <th style="width: 15%;">QR Code</th>
                    <th style="width: 35%;">Descrição do Material</th>
                    <th style="width: 15%;">Nº Série</th>
                    <th style="width: 12%;">Valor (R$)</th>
                    <th style="width: 15%;">Observações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($custodyLog->assets as $index => $asset)
                <tr>
                    <td class="text-center font-bold">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="font-mono text-sm">{{ $asset->qr_code }}</td>
                    <td>{{ $asset->name }}</td>
                    <td class="font-mono text-sm">{{ $asset->serial_number ?? '-' }}</td>
                    <td class="text-right">{{ $asset->purchase_value ? 'R$ ' . number_format($asset->purchase_value, 2, ',', '.') : '-' }}</td>
                    <td class="text-sm">{{ $asset->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray font-bold">
                    <td colspan="4" class="text-right" style="padding: 12px;">
                        TOTAL DE ITENS: {{ $custodyLog->assets->count() }}
                    </td>
                    <td class="text-right" style="padding: 12px;">
                        R$ {{ number_format($custodyLog->assets->sum('purchase_value') ?? 0, 2, ',', '.') }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        {{-- Observações --}}
        @if($custodyLog->notes)
        <div style="margin-bottom: 30px;">
            <div class="font-bold text-lg border-b mb-2" style="text-transform: uppercase; padding-bottom: 5px;">
                Observações Gerais
            </div>
            <div style="border: 1px solid #000; padding: 15px; min-height: 60px; background-color: #fafafa;">
                {{ $custodyLog->notes }}
            </div>
        </div>
        @endif

        {{-- Termos de Responsabilidade --}}
        <div class="font-bold text-lg border-b mb-2" style="text-transform: uppercase; padding-bottom: 5px;">
            Termos de Responsabilidade
        </div>
        <div style="font-size: 11pt; text-align: justify; margin-bottom: 30px; line-height: 1.6;">
            <p style="margin-bottom: 12px;">
                <strong>1.</strong> Declaro ter recebido em perfeito estado de conservação e funcionamento os materiais 
                relacionados neste termo, comprometendo-me a utilizá-los exclusivamente para fins de serviço.
            </p>
            <p style="margin-bottom: 12px;">
                <strong>2.</strong> Responsabilizo-me pela guarda, conservação e bom uso dos materiais, devendo 
                comunicar imediatamente qualquer avaria, perda ou furto à Seção de Tecnologia da Informação.
            </p>
            <p style="margin-bottom: 12px;">
                <strong>3.</strong> Comprometo-me a devolver os materiais nas mesmas condições de recebimento, 
                quando solicitado ou ao cessar a necessidade de uso.
            </p>
            <p style="margin-bottom: 12px;">
                <strong>4.</strong> Estou ciente de que a não devolução ou danos causados por mau uso 
                resultarão em responsabilização disciplinar e/ou pecuniária conforme regulamentação vigente.
            </p>
            <p>
                <strong>5.</strong> Este termo substitui qualquer cautela anterior referente aos materiais 
                aqui relacionados.
            </p>
        </div>

        {{-- Área de Assinaturas --}}
        <div style="margin-top: 50px;">
            <table>
                <tbody>
                    <tr>
                        <td style="width: 50%; text-align: center; padding: 40px 20px; border: none;">
                            <div style="border-top: 1px solid #000; padding-top: 10px; margin-top: 60px;">
                                <strong>{{ $custodyLog->user->rank ?? '' }} {{ $custodyLog->user->name ?? 'N/A' }}</strong><br>
                                <span class="text-sm">RESPONSÁVEL PELO MATERIAL</span><br>
                                <span class="text-sm">ID: {{ $custodyLog->user->military_id ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td style="width: 50%; text-align: center; padding: 40px 20px; border: none;">
                            <div style="border-top: 1px solid #000; padding-top: 10px; margin-top: 60px;">
                                <strong>SEÇÃO DE TECNOLOGIA DA INFORMAÇÃO</strong><br>
                                <span class="text-sm">RESPONSÁVEL PELA ENTREGA</span><br>
                                <span class="text-sm">SGTI-GAC</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Rodapé --}}
        <div style="margin-top: 40px; text-align: center; font-size: 9pt; color: #666; border-top: 1px solid #ccc; padding-top: 15px;">
            <p>
                Documento gerado automaticamente pelo Sistema SGAITI em {{ now()->format('d/m/Y H:i:s') }}
            </p>
            <p>
                Cautela Nº {{ $custodyLog->cautela_number }} | Comando da Aeronáutica - GAC/MS
            </p>
        </div>
    </div>
</div>
