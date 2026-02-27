    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nova Cautela') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form wire:submit="save">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Cautela Number -->
                            <div>
                                <x-input-label for="cautela_number" :value="__('Número da Cautela')" />
                                <x-text-input wire:model="cautela_number" id="cautela_number" class="block mt-1 w-full" type="text" required autofocus />
                                <x-input-error :messages="$errors->get('cautela_number')" class="mt-2" />
                            </div>

                            <!-- User Selection -->
                            <div>
                                <x-input-label for="user_id" :value="__('Usuário Militar')" />
                                <select wire:model="user_id" id="user_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="">{{ __('Selecione um usuário') }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->rank }} {{ $user->name }} ({{ $user->military_id }})</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                            </div>

                            <!-- Checkout Date -->
                            <div>
                                <x-input-label for="checkout_date" :value="__('Data de Saída')" />
                                <x-text-input wire:model="checkout_date" id="checkout_date" class="block mt-1 w-full" type="date" required />
                                <x-input-error :messages="$errors->get('checkout_date')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Asset Selection -->
                        <div class="mt-6 border-t pt-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Seleção de Ativos') }}</h3>
                                <div class="text-sm text-gray-500">
                                    {{ count($selectedAssets) }} {{ Str::plural('item', count($selectedAssets)) }} {{ __('selecionado(s)') }}
                                </div>
                            </div>

                            <!-- Search & Filter -->
                            <div class="mb-4">
                                <x-text-input wire:model.live.debounce.300ms="searchAsset" placeholder="Buscar ativo por nome, patrimônio ou QR code..." class="w-full" />
                            </div>

                            <!-- Assets Table -->
                            <div class="border rounded-md overflow-hidden max-h-96 overflow-y-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50 sticky top-0">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-10">
                                                <span class="sr-only">{{ __('Selecionar') }}</span>
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Ativo') }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Patrimônio') }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Localização') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($this->availableAssets as $asset)
                                            <tr class="hover:bg-gray-50 cursor-pointer" wire:click="toggleAsset({{ $asset->id }})">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="checkbox" wire:model.live="selectedAssets" value="{{ $asset->id }}" class="rounded border-gray-300 text-fab-blue shadow-sm focus:ring-fab-blue">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $asset->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $asset->qr_code }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $asset->patrimony_number }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $asset->location }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                                    {{ __('Nenhum ativo disponível encontrado.') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <x-input-error :messages="$errors->get('selectedAssets')" class="mt-2" />
                            
                            @if(count($selectedAssets) > 0)
                                <div class="mt-2 text-right">
                                    <button type="button" wire:click="$set('selectedAssets', [])" class="text-sm text-red-600 hover:text-red-900">{{ __('Limpar Seleção') }}</button>
                                </div>
                            @endif
                        </div>

                        <!-- Notes -->
                        <div class="mt-6">
                            <x-input-label for="notes" :value="__('Observações')" />
                            <textarea wire:model="notes" id="notes" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="3"></textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t">
                            <a href="{{ route('custody.index') }}" class="text-gray-600 hover:text-gray-900">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button>
                                {{ __('Criar Cautela') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
