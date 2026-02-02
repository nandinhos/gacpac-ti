<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Inventário: ') }} {{ $inventory->commission_number }}
        </h2>
    </x-slot>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <div class="py-6" x-data="inventoryScanner()">
        <!-- Toolbar -->
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4 p-4 bg-white shadow sm:rounded-lg">
            <div class="flex items-center space-x-4">
                <span class="px-3 py-1 text-sm font-medium rounded-full {{ $inventory->status === 'Concluído' ? 'bg-green-100 text-green-800' : ($inventory->status === 'Reaberto' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                    {{ $inventory->status }}
                </span>
                @if($inventory->is_commission)
                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-purple-100 text-indigo-800">
                        {{ __('Comissão') }}
                    </span>
                @endif
            </div>
            
            <div class="flex items-center space-x-2">
                @if($inventory->status === 'Concluído')
                <button wire:click="openReopenModal" class="px-4 py-2 text-sm font-medium text-yellow-700 bg-yellow-100 border border-yellow-300 rounded-md shadow-sm hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    {{ __('Reabrir Inventário') }}
                </button>
                @endif
                <a href="{{ route('inventory.pdf', $inventory) }}" target="_blank" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Relatório PDF') }}
                </a>
                <a href="{{ route('inventory.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Voltar') }}
                </a>
            </div>
        </div>

    <div class="py-6">
        <!-- Scanner Area -->
        @if($inventory->status !== 'Concluído')
        <div class="mb-6 bg-white shadow sm:rounded-lg">
            <div class="p-6">
                <form wire:submit.prevent="findAsset" class="flex flex-col md:flex-row items-center gap-4">
                    <div class="flex-1 w-full relative">
                        <x-text-input 
                            wire:model="qrCodeInput" 
                            type="text" 
                            class="w-full pl-12" 
                            placeholder="Escanear ou digitar QR Code / Serial Number..." 
                            autofocus 
                        />
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 16h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                        </div>
                        <x-input-error :messages="$errors->get('qrCodeInput')" class="mt-2" />
                    </div>
                    
                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="button" @click="startScan" class="flex items-center justify-center px-4 py-2 bg-indigo-100 border border-indigo-300 rounded-md font-semibold text-xs text-indigo-700 uppercase tracking-widest hover:bg-indigo-200 active:bg-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full md:w-auto">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ __('Câmera') }}
                        </button>

                        <x-primary-button type="submit" class="w-full md:w-auto flex justify-center">
                            {{ __('Encontrar') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Scanner Modal -->
        <div x-show="scanning" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="stopScan">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start justify-center">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 text-center">
                                    Escanear QR Code
                                </h3>
                                <div id="reader" class="w-full"></div>
                                <p class="text-sm text-gray-500 mt-2 text-center">Aponte a câmera para o código do ativo.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="stopScan" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function inventoryScanner() {
                return {
                    scanning: false,
                    html5QrcodeScanner: null,
                    
                    startScan() {
                        this.scanning = true;
                        this.$nextTick(() => {
                            if (typeof Html5QrcodeScanner === 'undefined') {
                                console.error("Html5QrcodeScanner not loaded");
                                alert("Erro: Biblioteca de scanner não carregada. Verifique sua conexão.");
                                this.scanning = false;
                                return;
                            }

                            if (this.html5QrcodeScanner === null) {
                                this.html5QrcodeScanner = new Html5QrcodeScanner(
                                    "reader", 
                                    { fps: 10, qrbox: { width: 250, height: 250 } },
                                    /* verbose= */ false
                                );
                            }
                            this.html5QrcodeScanner.render(this.onScanSuccess.bind(this), this.onScanFailure);
                        });
                    },

                    stopScan() {
                        if (this.html5QrcodeScanner) {
                            this.html5QrcodeScanner.clear().then(() => {
                                this.scanning = false;
                            }).catch(error => {
                                console.error("Failed to clear scanner", error);
                                this.scanning = false;
                            });
                        } else {
                            this.scanning = false;
                        }
                    },

                    onScanSuccess(decodedText, decodedResult) {
                        // Play beep sound (optional)
                        // new Audio('/beep.mp3').play().catch(e => {}); 

                        this.stopScan();
                        
                        // Set Livewire property and submit
                        @this.set('qrCodeInput', decodedText);
                        
                        // Small delay to ensure property is set before submitting
                        setTimeout(() => {
                            @this.findAsset();
                        }, 200);
                    },

                    onScanFailure(error) {
                        // handle scan failure, usually better to ignore and keep scanning.
                        // console.warn(`Code scan error = ${error}`);
                    }
                }
            }
        </script>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Pendentes -->
            <div class="flex flex-col bg-white shadow sm:rounded-lg">
                <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        @if($inventory->status !== 'Concluído')
                        <input type="checkbox" wire:model.live="selectAllPending" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        @endif
                        <h3 class="text-lg font-medium text-red-600">Pendentes ({{ $pendingAssets->count() }})</h3>
                    </div>
                    @if($selectedPending && $inventory->status !== 'Concluído')
                    <button wire:click="bulkFind" class="text-sm font-medium text-green-600 hover:text-green-800">
                        Marcar Selecionados ({{ count($selectedPending) }})
                    </button>
                    @endif
                </div>
                <div class="flex-1 p-0 overflow-y-auto max-h-[500px]">
                    <ul class="divide-y divide-gray-200">
                        @forelse($pendingAssets as $asset)
                        <li wire:key="pending-{{ $asset->id }}" class="p-4 hover:bg-gray-50">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                @if($inventory->status !== 'Concluído')
                                <input type="checkbox" wire:model.live="selectedPending" value="{{ $asset->id }}" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                @endif
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
                    <div class="flex items-center space-x-2">
                        @if($inventory->status !== 'Concluído')
                        <input type="checkbox" wire:model.live="selectAllFound" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        @endif
                        <h3 class="text-lg font-medium text-green-600">Conferidos ({{ $foundAssets->count() }})</h3>
                    </div>
                    @if($selectedFound && $inventory->status !== 'Concluído')
                    <button wire:click="bulkRemove" class="text-sm font-medium text-orange-600 hover:text-orange-800">
                        Remover Selecionados ({{ count($selectedFound) }})
                    </button>
                    @endif
                </div>
                <div class="flex-1 p-0 overflow-y-auto max-h-[500px]">
                    <ul class="divide-y divide-gray-200">
                        @forelse($foundAssets as $asset)
                        <li wire:key="found-{{ $asset->id }}" class="p-4 hover:bg-gray-50">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                @if($inventory->status !== 'Concluído')
                                <input type="checkbox" wire:model.live="selectedFound" value="{{ $asset->id }}" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                @endif
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
                        <li wire:key="uncatalogued-{{ $item['id'] }}" class="p-4 flex justify-between items-center group">
                            @if($editingUncataloguedId === $item['id'])
                                <div class="flex-1 mr-4">
                                    <form wire:submit.prevent="updateUncatalogued" class="flex gap-2">
                                        <x-text-input wire:model="editingDescription" class="w-full text-sm py-1" />
                                        <button type="submit" class="text-green-600 hover:text-green-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                        <button type="button" wire:click="cancelEditUncatalogued" class="text-gray-500 hover:text-gray-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div>
                                    <p class="text-sm text-gray-900">{{ $item['description'] }}</p>
                                    <p class="text-xs text-gray-500 italic">
                                        {{ __('Encontrado em: ') }} {{ \Carbon\Carbon::parse($item['created_at'])->format('d/m/Y') }}
                                        @if(isset($item['creator_name']))
                                            by {{ $item['creator_name'] }}
                                        @endif
                                    </p>
                                </div>
                            @endif

                            @if($inventory->status !== 'Concluído' && $editingUncataloguedId !== $item['id'])
                            <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition">
                                @if($item['created_by_user_id'] === auth()->id() || auth()->user()->role === 'admin')
                                <button wire:click="editUncatalogued({{ $item['id'] }})" class="text-blue-500 hover:text-blue-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                @endif
                                
                                <button wire:click="removeUncatalogued({{ $item['id'] }})" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                            @endif
                        </li>
                        @empty
                        <li class="p-8 text-center text-gray-500 italic">Nenhum item extra.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="p-4 border-t border-gray-200">
                    @if($inventory->status !== 'Concluído')
                    <form wire:submit.prevent="addUncatalogued" class="flex items-center space-x-2">
                        <x-text-input wire:model="uncataloguedDescription" type="text" class="flex-1 text-sm" placeholder="Descrever item extra..." />
                        <x-secondary-button type="submit" class="!py-1.5 !px-3">
                            {{ __('Adicionar') }}
                        </x-secondary-button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer / Observation -->
        <div class="mt-8 bg-white shadow sm:rounded-lg">
            <div class="p-6">
                <div class="mb-4">
                    <x-input-label for="notes" :value="__('Observações de Auditoria')" />
                    <textarea wire:model="notes" id="notes" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" @if($inventory->status === 'Concluído') readonly disabled @endif></textarea>
                </div>
                @if($inventory->status !== 'Concluído')
                <div class="flex justify-end">
                    <x-primary-button wire:click="openFinalizeModal" class="bg-green-600 hover:bg-green-700 active:bg-green-900">
                        {{ __('Finalizar Inventário') }}
                    </x-primary-button>
                </div>
                @endif
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
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    rows="3"
                    placeholder="{{ __('Motivo da reabertura...') }}"
                ></textarea>

                <x-input-error :messages="$errors->get('reopenJustification')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button wire:click="closeReopenModal">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-primary-button class="ml-3" wire:click="confirmReopen">
                    {{ __('Confirmar Reabertura') }}
                </x-primary-button>
            </div>
        </div>
    </x-modal>

    <x-modal name="finalize-modal" :show="$showFinalizeModal" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Confirmar Finalização de Inventário') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Ao finalizar, este inventário será marcado como concluído e não poderá mais ser editado sem uma reabertura formal.') }}
            </p>

            <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="text-sm font-bold text-gray-700 uppercase mb-2">{{ __('Resumo da Conferência') }}</h3>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="p-2">
                        <p class="text-xs text-gray-500">{{ __('Conferidos') }}</p>
                        <p class="text-lg font-bold text-green-600">{{ $foundAssets->count() }}</p>
                    </div>
                    <div class="p-2">
                        <p class="text-xs text-gray-500">{{ __('Pendentes') }}</p>
                        <p class="text-lg font-bold text-red-600">{{ $pendingAssets->count() }}</p>
                    </div>
                    <div class="p-2">
                        <p class="text-xs text-gray-500">{{ __('Extras') }}</p>
                        <p class="text-lg font-bold text-indigo-600">{{ $uncataloguedItems->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button wire:click="closeFinalizeModal">
                    {{ __('Continuar Conferência') }}
                </x-secondary-button>

                <x-primary-button class="ml-3 bg-green-600 hover:bg-green-700" wire:click="finalize">
                    {{ __('Sim, Finalizar Agora') }}
                </x-primary-button>
            </div>
        </div>
    </x-modal>
</div>
