<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inventário') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white/70 backdrop-blur-md shadow-sm sm:rounded-lg border border-white/20">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
                        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto flex-1">
                            <div class="flex-1 relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400 group-focus-within:text-fab-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <x-text-input wire:model.live="search" type="text" class="block w-full pl-10 pr-3 py-2 border-gray-200 rounded-xl bg-white/50 focus:border-fab-blue focus:ring-fab-blue sm:text-sm shadow-sm transition-all" placeholder="Buscar por comissão..." />
                            </div>
                            <div class="w-full md:w-48">
                                <select wire:model.live="sector_id" class="w-full border-gray-200 rounded-xl bg-white/50 shadow-sm focus:border-fab-blue focus:ring-fab-blue sm:text-sm transition-all">
                                    <option value="">Todos os Setores</option>
                                    @foreach($sectors as $sector)
                                        <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <a href="{{ route('inventory.create') }}" class="inline-flex items-center px-4 py-2 bg-fab-blue border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-fab-blue-hover active:bg-fab-blue-hover focus:outline-none focus:ring-2 focus:ring-fab-blue focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-fab-blue/20 whitespace-nowrap">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Novo Inventário') }}
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50/50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-semibold tracking-wider">Inventário</th>
                                    <th scope="col" class="px-6 py-3 font-semibold tracking-wider">Setor</th>
                                    <th scope="col" class="px-6 py-3 font-semibold tracking-wider">Responsável</th>
                                    <th scope="col" class="px-6 py-3 font-semibold tracking-wider">Início</th>
                                    <th scope="col" class="px-6 py-3 font-semibold tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right font-semibold tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($inventories as $inventory)
                                    <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 font-bold text-gray-900">
                                            {{ $inventory->commission_number }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-gray-900">{{ $inventory->sector->name ?? 'Geral (Todos)' }}</span>
                                                @if($inventory->is_commission)
                                                    <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800 border border-purple-200 uppercase w-fit tracking-wider">
                                                        {{ __('Comissão') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $inventory->responsibleUser->name ?? 'Não atribuído' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $inventory->start_date->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-bold rounded-full 
                                                {{ $inventory->status === 'Concluído' ? 'bg-green-100 text-green-800' : 
                                                   ($inventory->status === 'Reaberto' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                                {{ $inventory->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('inventory.show', $inventory) }}" class="text-green-600 hover:text-green-900 transition-colors" title="Ver Detalhes">
                                                    <svg class="w-5 h-5 transition-transform hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                
                                                @if($inventory->status === 'Concluído')
                                                <button wire:click="openReopenModal({{ $inventory->id }})" class="text-yellow-600 hover:text-yellow-900 transition-colors" title="Reabrir">
                                                    <svg class="w-5 h-5 transition-transform hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </button>
                                                @endif

                                                @if($inventory->status !== 'Concluído')
                                                <button wire:click="delete({{ $inventory->id }})" wire:confirm="Tem certeza que deseja excluir este inventário?" class="text-red-600 hover:text-red-900 transition-colors" title="Excluir">
                                                    <svg class="w-5 h-5 transition-transform hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white">
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-400 italic">{{ __('Nenhum inventário encontrado.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8">
                        {{ $inventories->links() }}
                    </div>
                </div>
            </div>
        </div>

    <x-modal name="reopen-modal" :show="$showReopenModal" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Reabrir Inventário') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Por favor, informe uma justificativa para reabrir este inventário.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="justification" value="{{ __('Justificativa') }}" class="sr-only" />

                <textarea
                    wire:model="reopenJustification"
                    id="justification"
                    class="w-full border-gray-300 rounded-xl shadow-sm focus:border-fab-blue focus:ring focus:ring-fab-blue/20"
                    rows="3"
                    placeholder="{{ __('Motivo da reabertura...') }}"
                ></textarea>

                <x-input-error :messages="$errors->get('reopenJustification')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button wire:click="closeReopenModal" class="rounded-xl font-bold">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-primary-button wire:click="confirmReopen" class="bg-fab-blue hover:bg-fab-blue-hover active:bg-fab-blue-hover border-transparent rounded-xl font-bold shadow-lg shadow-fab-blue/20">
                    {{ __('Confirmar Reabertura') }}
                </x-primary-button>
            </div>
        </div>
    </x-modal>
</div>
