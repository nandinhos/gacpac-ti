<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-100">
        <thead>
            <tr>
                <th class="px-2 py-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('Ativo') }}</th>
                <th class="px-2 py-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('QR Code') }}</th>
                <th class="px-2 py-2 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('Ações') }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($assets as $asset)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-2 py-3">
                        <div class="text-xs font-semibold text-gray-800">{{ $asset->name }}</div>
                        <div class="text-[10px] text-gray-400">{{ $asset->brand }} / {{ $asset->model }}</div>
                    </td>
                    <td class="px-2 py-3 whitespace-nowrap text-[10px] font-mono {{ ($isCustody ?? false) ? 'text-orange-600' : 'text-fab-blue' }}">
                        {{ $asset->qr_code }}
                    </td>
                    <td class="px-2 py-3 whitespace-nowrap text-right">
                        <a href="{{ route('assets.edit', $asset) }}" class="text-gray-400 hover:text-fab-blue transition-colors" title="Ver Detalhes do Ativo" wire:navigate>
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
