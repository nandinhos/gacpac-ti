<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Inventário: ') }} {{ $inventory->commission_number }}
            </h2>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 text-sm font-medium rounded-full {{ $inventory->status === 'Concluído' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ $inventory->status }}
                </span>
                <a href="{{ route('inventory.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Voltar') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <!-- Scanner Area -->
        <div class="mb-6 bg-white shadow sm:rounded-lg">
            <div class="p-6">
                <form wire:submit.prevent="findAsset" class="flex items-center space-x-4">
                    <div class="flex-1">
                        <x-text-input wire:model="qrCodeInput" type="text" class="w-full" placeholder="Escanear ou digitar QR Code / Serial Number..." autofocus />
                        <x-input-error :messages="$errors->get('qrCodeInput')" class="mt-2" />
                    </div>
                    <x-primary-button type="submit">
                        {{ __('Encontrar') }}
                    </x-primary-button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Pendentes -->
            <div class="flex flex-col bg-white shadow sm:rounded-lg">
                <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-red-600">Pendentes ({{ $pendingAssets->count() }})</h3>
                    @if($selectedPending)
                    <button wire:click="bulkFind" class="text-sm font-medium text-green-600 hover:text-green-800">
                        Marcar Selecionados ({{ count($selectedPending) }})
                    </button>
                    @endif
                </div>
                <div class="flex-1 p-0 overflow-y-auto max-h-[500px]">
                    <ul class="divide-y divide-gray-200">
                        @forelse($pendingAssets as $asset)
                        <li class="p-4 hover:bg-gray-50">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" wire:model.live="selectedPending" value="{{ $asset->id }}" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $asset->name }}</p>
                                    <p class="text-xs text-gray-500 font-mono">{{ $asset->qr_code ?? $asset->serial_number }}</p>
                                </div>
                            </label>
                        </li>
                        @empty
                        <li class="p-8 text-center text-gray-500 italic">Nenhum item pendente.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Conferidos -->
            <div class="flex flex-col bg-white shadow sm:rounded-lg">
                <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-green-600">Conferidos ({{ $foundAssets->count() }})</h3>
                    @if($selectedFound)
                    <button wire:click="bulkRemove" class="text-sm font-medium text-orange-600 hover:text-orange-800">
                        Remover Selecionados ({{ count($selectedFound) }})
                    </button>
                    @endif
                </div>
                <div class="flex-1 p-0 overflow-y-auto max-h-[500px]">
                    <ul class="divide-y divide-gray-200">
                        @forelse($foundAssets as $asset)
                        <li class="p-4 hover:bg-gray-50">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" wire:model.live="selectedFound" value="{{ $asset->id }}" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $asset->name }}</p>
                                    <p class="text-xs text-gray-500 font-mono">{{ $asset->qr_code ?? $asset->serial_number }}</p>
                                </div>
                            </label>
                        </li>
                        @empty
                        <li class="p-8 text-center text-gray-500 italic">Nenhum item conferido.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Não Catalogados -->
            <div class="flex flex-col bg-white shadow sm:rounded-lg">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900">Não Catalogados ({{ $uncataloguedItems->count() }})</h3>
                </div>
                <div class="flex-1 p-0 overflow-y-auto max-h-[400px]">
                    <ul class="divide-y divide-gray-200">
                        @forelse($uncataloguedItems as $item)
                        <li class="p-4 flex justify-between items-center group">
                            <div>
                                <p class="text-sm text-gray-900">{{ $item->description }}</p>
                                <p class="text-xs text-gray-500 italic">{{ __('Encontrado em: ') }} {{ $item->found_date->format('d/m/Y') }}</p>
                            </div>
                            <button wire:click="removeUncatalogued({{ $item->id }})" class="opacity-0 group-hover:opacity-100 text-red-500 hover:text-red-700 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </li>
                        @empty
                        <li class="p-8 text-center text-gray-500 italic">Nenhum item extra.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="p-4 border-t border-gray-200">
                    <form wire:submit.prevent="addUncatalogued" class="flex items-center space-x-2">
                        <x-text-input wire:model="uncataloguedDescription" type="text" class="flex-1 text-sm" placeholder="Descrever item extra..." />
                        <x-secondary-button type="submit" class="!py-1.5 !px-3">
                            {{ __('Adicionar') }}
                        </x-secondary-button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer / Observation -->
        <div class="mt-8 bg-white shadow sm:rounded-lg">
            <div class="p-6">
                <div class="mb-4">
                    <x-input-label for="notes" :value="__('Observações de Auditoria')" />
                    <textarea wire:model="notes" id="notes" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                </div>
                <div class="flex justify-end">
                    <x-primary-button wire:click="finalize" class="bg-green-600 hover:bg-green-700 active:bg-green-900">
                        {{ __('Finalizar Inventário') }}
                    </x-primary-button>
                </div>
            </div>
        </div>
    </div>
</div>
