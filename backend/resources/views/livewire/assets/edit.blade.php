
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Ativo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form wire:submit="save" class="space-y-6">
                        <!-- Basic Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Nome do Ativo / Descrição')" />
                                <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="qr_code" :value="__('Código QR')" />
                                <x-text-input wire:model="qr_code" id="qr_code" class="block mt-1 w-full bg-gray-50" type="text" readonly />
                                <x-input-error :messages="$errors->get('qr_code')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="brand" :value="__('Marca')" />
                                <x-text-input wire:model="brand" id="brand" class="block mt-1 w-full" type="text" />
                                <x-input-error :messages="$errors->get('brand')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="model" :value="__('Modelo')" />
                                <x-text-input wire:model="model" id="model" class="block mt-1 w-full" type="text" />
                                <x-input-error :messages="$errors->get('model')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Identification -->
                        <div class="border-t pt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="serial_number" :value="__('Número de Série')" />
                                <x-text-input wire:model="serial_number" id="serial_number" class="block mt-1 w-full" type="text" />
                                <x-input-error :messages="$errors->get('serial_number')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="patrimony_number" :value="__('Número de Patrimônio')" />
                                <x-text-input wire:model="patrimony_number" id="patrimony_number" class="block mt-1 w-full" type="text" />
                                <x-input-error :messages="$errors->get('patrimony_number')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Classification & Status -->
                        <div class="border-t pt-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="type" :value="__('Tipo')" />
                                <select wire:model="type" id="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @foreach($types as $typeOption)
                                        <option value="{{ $typeOption }}">{{ $typeOption }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select wire:model="status" id="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @foreach($statuses as $statusOption)
                                        <option value="{{ $statusOption }}">{{ $statusOption }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="condition" :value="__('Condição')" />
                                <select wire:model="condition" id="condition" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @foreach($conditions as $conditionOption)
                                        <option value="{{ $conditionOption }}">{{ $conditionOption }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('condition')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="border-t pt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="sector_id" :value="__('Setor')" />
                                <select wire:model="sector_id" id="sector_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">{{ __('Selecione um Setor') }}</option>
                                    @foreach($sectors as $sector)
                                        <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('sector_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="location" :value="__('Localização Específica (Sala/Mesa)')" />
                                <x-text-input wire:model="location" id="location" class="block mt-1 w-full" type="text" placeholder="Ex: Sala 101, Mesa 3" />
                                <x-input-error :messages="$errors->get('location')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Financial & Notes -->
                        <div class="border-t pt-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="acquisition_date" :value="__('Data de Aquisição')" />
                                <x-text-input wire:model="acquisition_date" id="acquisition_date" class="block mt-1 w-full" type="date" />
                                <x-input-error :messages="$errors->get('acquisition_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="warranty_expiry" :value="__('Expiração da Garantia')" />
                                <x-text-input wire:model="warranty_expiry" id="warranty_expiry" class="block mt-1 w-full" type="date" />
                                <x-input-error :messages="$errors->get('warranty_expiry')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="purchase_value" :value="__('Valor de Compra (R$)' )" />
                                <x-text-input wire:model="purchase_value" id="purchase_value" class="block mt-1 w-full" type="number" step="0.01" />
                                <x-input-error :messages="$errors->get('purchase_value')" class="mt-2" />
                            </div>

                            <div class="col-span-2">
                                <x-input-label for="notes" :value="__('Observações')" />
                                <textarea wire:model="notes" id="notes" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3"></textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Salvar') }}</x-primary-button>
                            <a href="{{ route('assets.index') }}" class="text-gray-600 hover:text-gray-900">{{ __('Cancelar') }}</a>
                            <a href="{{ route('maintenance.index', $asset) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ __('Manutenções') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
