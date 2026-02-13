
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes da Cautela') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Status Header -->
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">
                            {{ $cautela_number }}
                            @if($checkin_date)
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ __('BAIXADA') }} - {{ $checkin_date }}
                                </span>
                            @else
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ __('ABERTA') }}
                                </span>
                            @endif
                        </h3>
                        
                        @if(!$checkin_date)
                            <button wire:click="closeCustody" wire:confirm="Tem certeza que deseja baixar esta cautela? Todos os ativos voltarão a ficar DISPONÍVEIS." class="px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 transition ease-in-out duration-150">
                                {{ __('Baixar Cautela (Check-in)') }}
                            </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <x-input-label :value="__('Usuário Militar')" />
                            <div class="text-gray-900 font-medium mt-1">{{ $user_name }}</div>
                        </div>

                        <div>
                            <x-input-label :value="__('Data de Saída')" />
                            <div class="text-gray-900 font-medium mt-1">{{ $checkout_date }}</div>
                        </div>
                    </div>

                    <!-- Assets List -->
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Ativos na Cautela') }}</h3>
                        </div>

                        @if(!$checkin_date)
                            <div class="mb-6 bg-gray-50 p-4 rounded-md border text-sm">
                                <h4 class="font-medium text-gray-700 mb-2">{{ __('Adicionar Ativo') }}</h4>
                                <div class="relative">
                                    <x-text-input wire:model.live.debounce.300ms="searchAsset" placeholder="Buscar para adicionar..." class="w-full" />
                                    
                                    @if(strlen($searchAsset) > 2)
                                        <div class="absolute z-10 w-full bg-white border border-gray-300 mt-1 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                            @forelse($availableAssets as $avail)
                                                <button wire:click="addAsset({{ $avail->id }})" class="w-full text-left px-4 py-2 hover:bg-indigo-50 flex justify-between items-center group">
                                                    <div>
                                                        <div class="font-medium text-gray-900">{{ $avail->name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $avail->patrimony_number }}</div>
                                                    </div>
                                                    <span class="text-indigo-600 font-semibold text-xs group-hover:underline">{{ __('Adicionar') }}</span>
                                                </button>
                                            @empty
                                                <div class="px-4 py-2 text-gray-500 text-xs">{{ __('Nenhum ativo encontrado.') }}</div>
                                            @endforelse
                                        </div>
                                    @endif
                                </div>
                                <x-input-error :messages="$errors->get('searchAsset')" class="mt-2" />
                            </div>
                        @endif

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Nome') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('QR Code') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Patrimônio') }}</th>
                                        @if(!$checkin_date)
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Ação') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($assets as $asset)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $asset->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $asset->qr_code }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $asset->patrimony_number }}</td>
                                            @if(!$checkin_date)
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                                    <button wire:click="removeAsset({{ $asset->id }})" class="text-red-600 hover:text-red-900 font-medium">{{ __('Remover') }}</button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    @if($assets->isEmpty())
                                        <tr>
                                            <td colspan="{{ $checkin_date ? 3 : 4 }}" class="px-6 py-4 text-center text-sm text-gray-500">{{ __('Nenhum ativo nesta cautela atualmente.') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($notes)
                    <div class="border-t pt-4 mt-6">
                        <x-input-label :value="__('Observações')" />
                        <div class="mt-1 text-gray-700 bg-gray-50 p-3 rounded">{{ $notes }}</div>
                    </div>
                    @endif

                    <div class="mt-6 flex items-center justify-end">
                        <a href="javascript:history.back()" class="text-gray-600 hover:text-gray-900">{{ __('Voltar') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
