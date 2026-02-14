<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ativos') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-md overflow-hidden shadow-sm sm:rounded-lg border border-white/20">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
                        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto flex-1">
                            <div class="flex-1 relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400 group-focus-within:text-fab-blue transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar ativos..." class="block w-full pl-10 pr-3 py-2 border-gray-200 rounded-xl bg-white/50 focus:border-fab-blue focus:ring-fab-blue sm:text-sm shadow-sm transition-all">
                            </div>
                        </div>

                        <a href="{{ route('assets.create') }}" class="inline-flex items-center px-4 py-2 bg-fab-blue border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-fab-blue-hover active:bg-fab-blue-hover focus:outline-none focus:ring-2 focus:ring-fab-blue focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-fab-blue/20 whitespace-nowrap">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Novo Ativo') }}
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('name')">
                                        {{ __('Nome/Modelo') }}
                                        @if($sortField === 'name')
                                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('patrimony_number')">
                                        {{ __('Ident.') }}
                                        @if($sortField === 'patrimony_number')
                                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('type')">
                                        {{ __('Tipo') }}
                                        @if($sortField === 'type')
                                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('sector_id')">
                                        {{ __('Setor') }}
                                        @if($sortField === 'sector_id')
                                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('status')">
                                        {{ __('Status') }}
                                        @if($sortField === 'status')
                                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        {{ __('Ações') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($assets as $asset)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="text-sm font-medium text-gray-900">{{ $asset->name }}</div>
                                                @if($asset->is_modified)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-800 border border-orange-200 uppercase" title="Máquina Modificada / Upgrade">
                                                        Mod
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $asset->brand }} {{ $asset->model }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs text-gray-500">SN: {{ $asset->serial_number ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">Pat: {{ $asset->patrimony_number ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $asset->type ?? $asset->category }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $asset->sector->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                // Normalize status for color lookup (upper case, no accents for safety if needed, but direct map is easier)
                                                // Database has mixed values: "Disponível", "DISPONIVEL", "Em Uso", "Manutenção", etc.
                                                
                                                $status = $asset->status;
                                                $normalizedStatus = strtoupper(Str::slug($status, '_')); // converts "Em Uso" -> "EM_USO", "Manutenção" -> "MANUTENCAO"
                                                
                                                // Map normalized keys to colors
                                                $statusColors = [
                                                    'DISPONIVEL' => 'bg-green-100 text-green-800',
                                                    'EM_USO' => 'bg-blue-100 text-blue-800',
                                                    'MANUTENCAO' => 'bg-orange-100 text-orange-800',
                                                    'BAIXADO' => 'bg-red-800 text-red-100', // Vermelho escuro conforme solicitado
                                                ];

                                                // Fallback to direct match if slug conversion misses something unique, or default
                                                $colorClass = $statusColors[$normalizedStatus] ?? 
                                                              $statusColors[$status] ?? 
                                                              'bg-gray-100 text-gray-800';
                                                
                                                // Label: Use DB value if it looks nice, or format it
                                                // If uppercase like DISPONIVEL, make it Title Case. If mixed like "Em Uso", keep it.
                                                $statusLabel = ctype_upper(str_replace('_', '', $status)) 
                                                    ? str_replace('_', ' ', ucfirst(strtolower($status)))
                                                    : $status;
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('assets.edit', $asset) }}" class="text-fab-blue hover:text-fab-blue-hover" title="Editar" wire:navigate>
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <button wire:click="confirmDelete({{ $asset->id }})" class="text-red-600 hover:text-red-900" title="Excluir">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            {{ __('Nenhum ativo encontrado.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $assets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação -->
    <x-confirm-modal
        id="delete-modal"
        title="Excluir Ativo"
        message="Tem certeza que deseja excluir este ativo? Esta ação não pode ser desfeita."
        confirmText="Excluir"
        cancelText="Cancelar"
        confirmColor="red"
    >
        <button
            type="button"
            wire:click="delete"
            class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
        >
            Excluir
        </button>
    </x-confirm-modal>
</div>
