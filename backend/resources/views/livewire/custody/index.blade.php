    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cautelas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex flex-1 gap-4 w-full">
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por número, usuário ou ID militar..." class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full md:w-1/2">
                            
                            <select wire:model.live="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full md:w-1/4">
                                <option value="">{{ __('Todos Status') }}</option>
                                <option value="open">{{ __('Aberta') }}</option>
                                <option value="closed">{{ __('Baixada') }}</option>
                            </select>
                        </div>

                        <a href="{{ route('custody.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 whitespace-nowrap">
                            {{ __('Nova Cautela') }}
                        </a>
                    </div>

                    <!-- Desktop View -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Número') }}
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Usuário') }}
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Itens') }}
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Período') }}
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Status') }}
                                    </th>
                                    <th scope="col" class="relative px-4 py-3">
                                        <span class="sr-only">{{ __('Ações') }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($custodyLogs as $log)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $log->cautela_number }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="text-sm text-gray-900 font-medium">{{ $log->user->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $log->user->rank ?? '' }}</div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="text-sm text-gray-500">
                                                {{ $log->assets->count() }} {{ Str::plural('item', $log->assets->count()) }}
                                            </div>
                                            <div class="text-xs text-gray-400 truncate max-w-xs">
                                                {{ $log->assets->pluck('name')->join(', ') }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $log->checkout_date ? $log->checkout_date->format('d/m/Y') : '-' }}
                                            </div>
                                            @if($log->checkin_date)
                                                <div class="text-xs text-gray-500">
                                                    Dev: {{ $log->checkin_date->format('d/m/Y') }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                @if($log->checkin_date)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        {{ __('Baixada') }}
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        {{ __('Aberta') }}
                                                    </span>
                                                @endif
                                                @if($log->signed_term_url)
                                                    <span title="Documento assinado" class="text-blue-600">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                {{-- Detalhes --}}
                                                <a href="{{ route('custody.show', $log) }}" class="p-1.5 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded" wire:navigate title="{{ __('Detalhes') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </a>
                                                {{-- Imprimir --}}
                                                <a href="{{ route('custody.print', $log) }}" class="p-1.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded" target="_blank" title="{{ __('Imprimir') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                </a>
                                                {{-- Excluir --}}
                                                <button wire:click="delete({{ $log->id }})" wire:confirm="Tem certeza? Isso fará com que os ativos voltem para o status DISPONÍVEL." class="p-1.5 text-red-600 hover:text-red-900 hover:bg-red-50 rounded" title="{{ __('Excluir') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            {{ __('Nenhuma cautela encontrada.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile View (Cards) -->
                    <div class="md:hidden space-y-4">
                        @forelse ($custodyLogs as $log)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-bold text-gray-900">{{ $log->cautela_number }}</span>
                                    @if($log->checkin_date)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ __('Baixada') }}
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ __('Aberta') }}
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="mb-2">
                                    <div class="text-sm font-medium text-gray-900">{{ $log->user->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $log->user->rank ?? '' }}</div>
                                </div>

                                <div class="mb-3 text-sm text-gray-600">
                                    <span class="font-medium">{{ $log->assets->count() }} itens:</span> 
                                    <span class="text-gray-500 truncate block">{{ $log->assets->pluck('name')->join(', ') }}</span>
                                </div>

                                <div class="flex justify-between items-end mt-4 pt-3 border-t border-gray-200">
                                    <div class="text-xs text-gray-500">
                                        <div>Saída: {{ $log->checkout_date ? $log->checkout_date->format('d/m/Y') : '-' }}</div>
                                        @if($log->checkin_date)
                                            <div>Dev: {{ $log->checkin_date->format('d/m/Y') }}</div>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('custody.show', $log) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded" wire:navigate title="{{ __('Detalhes') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('custody.print', $log) }}" class="p-1.5 text-gray-600 hover:bg-gray-50 rounded" target="_blank" title="{{ __('Imprimir') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        </a>
                                        <button wire:click="delete({{ $log->id }})" wire:confirm="Tem certeza?" class="p-1.5 text-red-600 hover:bg-red-50 rounded" title="{{ __('Excluir') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-gray-500">
                                {{ __('Nenhuma cautela encontrada.') }}
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $custodyLogs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
