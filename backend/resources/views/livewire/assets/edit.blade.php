<div>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhamento do Ativo') }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                <span class="font-medium text-gray-700">{{ $asset->name }}</span>
                &middot; {{ $asset->qr_code }}
                @if($asset->serial_number)
                    &middot; S/N: {{ $asset->serial_number }}
                @endif
                @if($asset->is_modified)
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-orange-100 text-orange-800 border border-orange-200 uppercase tracking-wider">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Modificado
                    </span>
                @endif
            </p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <a href="javascript:history.back()" class="inline-flex items-center text-sm font-medium text-fab-blue hover:text-fab-blue-hover transition-colors group">
            <svg class="w-5 h-5 mr-1 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('Voltar') }}
        </a>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session()->has('message'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="p-4 bg-green-100 border border-green-400 text-green-700 rounded shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('message') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-700 hover:text-green-900 focus:outline-none rounded-full p-1 hover:bg-green-200 transition-colors">
                        <span class="sr-only">Fechar</span>
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Tabs Navigation -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button wire:click="$set('activeTab', 'dados')"
                                class="px-6 py-3 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'dados' ? 'border-fab-blue text-fab-blue' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ __('Dados do Ativo') }}
                        </button>

                        <button wire:click="$set('activeTab', 'fotos')"
                                class="px-6 py-3 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'fotos' ? 'border-fab-blue text-fab-blue' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ __('Fotos') }}
                            <span class="ml-1 px-2 py-0.5 text-xs rounded-full {{ $assetPhotos->count() > 0 ? 'bg-blue-100 text-fab-blue' : 'bg-gray-100 text-gray-500' }}">
                                {{ $assetPhotos->count() }}
                            </span>
                        </button>

                        <button wire:click="$set('activeTab', 'manutencao')"
                                class="px-6 py-3 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'manutencao' ? 'border-fab-blue text-fab-blue' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ __('Manutenções') }}
                            <span class="ml-1 px-2 py-0.5 text-xs rounded-full {{ $maintenanceCount > 0 ? 'bg-blue-100 text-fab-blue' : 'bg-gray-100 text-gray-500' }}">
                                {{ $maintenanceCount }}
                            </span>
                        </button>
                    </nav>
                </div>

                <!-- Tab: Dados do Ativo -->
                @if($activeTab === 'dados')
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

                        <div class="flex items-center justify-end gap-4 pt-4 border-t">
                            <a href="{{ route('assets.index') }}" class="text-gray-600 hover:text-gray-900">{{ __('Cancelar') }}</a>
                            <x-primary-button>{{ __('Salvar') }}</x-primary-button>
                        </div>
                    </form>
                </div>
                @endif

                @if($activeTab === 'manutencao')
                    <div class="p-6">
                        <livewire:maintenance.index :asset="$asset" :isEmbedded="true" />
                    </div>
                @endif

                <!-- Tab: Fotos -->
                @if($activeTab === 'fotos')
                <div class="p-6 space-y-8">

                    {{-- Flash message --}}
                    @if (session()->has('photo-message'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm font-medium">{{ session('photo-message') }}</span>
                        </div>
                    @endif

                    {{-- Upload Section --}}
                    <div>
                        <form wire:submit="savePhotos">
                            <div
                                x-data="{
                                    uploading: false,
                                    progress: 0,
                                    dragging: false,
                                    fileError: '',
                                    maxSizeMB: 10,
                                    allowedTypes: ['image/jpeg', 'image/png', 'image/webp'],
                                    validateFiles(input) {
                                        this.fileError = '';
                                        var files = input.files;
                                        if (!files.length) return true;
                                        for (var i = 0; i < files.length; i++) {
                                            var file = files[i];
                                            if (!this.allowedTypes.includes(file.type)) {
                                                this.fileError = 'O arquivo \'' + file.name + '\' tem formato invalido. Use JPG, PNG ou WEBP.';
                                                input.value = '';
                                                return false;
                                            }
                                            if (file.size > this.maxSizeMB * 1024 * 1024) {
                                                var sizeMB = (file.size / 1024 / 1024).toFixed(1);
                                                this.fileError = 'O arquivo \'' + file.name + '\' tem ' + sizeMB + ' MB. O maximo permitido e ' + this.maxSizeMB + ' MB.';
                                                input.value = '';
                                                return false;
                                            }
                                        }
                                        return true;
                                    }
                                }"
                                x-on:livewire-upload-start="uploading = true"
                                x-on:livewire-upload-finish="uploading = false; progress = 0"
                                x-on:livewire-upload-cancel="uploading = false"
                                x-on:livewire-upload-error="uploading = false; fileError = 'Falha no upload. Verifique o tamanho do arquivo e tente novamente.'"
                                x-on:livewire-upload-progress="progress = $event.detail.progress"
                                class="space-y-4"
                            >
                                {{-- Drop Zone --}}
                                <label for="uploadPhotos"
                                       x-on:dragover.prevent="dragging = true"
                                       x-on:dragleave.prevent="dragging = false"
                                       x-on:drop.prevent="dragging = false"
                                       :class="dragging ? 'border-indigo-400 bg-indigo-50 ring-2 ring-indigo-200' : 'border-gray-300 bg-white hover:border-indigo-300 hover:bg-gray-50'"
                                       class="relative flex flex-col items-center justify-center w-full py-8 border-2 border-dashed rounded-xl cursor-pointer transition-all duration-200">

                                    <div x-show="!uploading" class="flex flex-col items-center gap-2">
                                        <div class="p-3 rounded-full bg-blue-50">
                                            <svg class="w-8 h-8 text-fab-blue" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm text-gray-600">
                                                <span class="font-semibold text-fab-blue">Clique para selecionar</span>
                                                ou arraste arquivos aqui
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">JPG, JPEG, PNG ou WEBP &mdash; max. 10 MB por arquivo</p>
                                        </div>
                                    </div>

                                    {{-- Upload Progress --}}
                                    <div x-show="uploading" x-cloak class="w-full max-w-xs text-center">
                                        <div class="flex items-center justify-center gap-3 mb-2">
                                            <svg class="w-5 h-5 text-fab-blue animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-600" x-text="'Processando... ' + progress + '%'"></span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                            <div class="bg-indigo-500 h-2 rounded-full transition-all duration-300 ease-out"
                                                 :style="'width: ' + progress + '%'"></div>
                                        </div>
                                    </div>

                                    <input id="uploadPhotos" type="file" multiple
                                           accept="image/jpeg,image/png,image/webp" class="hidden"
                                           x-on:change="
                                               if (validateFiles($el)) {
                                                   uploading = true;
                                                   $wire.$uploadMultiple('uploadPhotos', [...$el.files],
                                                       () => { uploading = false; progress = 0; },
                                                       () => { uploading = false; fileError = 'Falha no upload. Verifique o tamanho do arquivo e tente novamente.'; },
                                                       (event) => { progress = event.detail.progress; }
                                                   )
                                               }
                                           " />
                                </label>

                                {{-- Erro de pré-validação (JS) --}}
                                <div x-show="fileError" x-cloak
                                     class="flex items-center gap-1.5 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-3 py-2">
                                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <span x-text="fileError"></span>
                                </div>

                                {{-- Erros de validacao (backend) --}}
                                @error('uploadPhotos')
                                    <div class="flex items-center gap-1.5 text-sm text-red-600">
                                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                                @error('uploadPhotos.*')
                                    <div class="flex items-center gap-1.5 text-sm text-red-600">
                                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror

                                {{-- Preview das fotos selecionadas --}}
                                @if($uploadPhotos && count($uploadPhotos) > 0)
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            {{ count($uploadPhotos) }} {{ count($uploadPhotos) === 1 ? 'foto selecionada' : 'fotos selecionadas' }}
                                        </p>
                                        <span class="text-xs text-gray-400">Pronto para enviar</span>
                                    </div>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach($uploadPhotos as $idx => $photo)
                                            @if($photo->isPreviewable())
                                            <div class="relative group" wire:key="preview-{{ $idx }}">
                                                <img src="{{ $photo->temporaryUrl() }}"
                                                     class="h-24 w-24 object-cover rounded-lg border-2 border-white shadow-sm" />
                                                <button type="button" wire:click="removePhoto({{ $idx }})"
                                                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600"
                                                        title="Remover">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                {{-- Caption + Submit --}}
                                <div class="flex items-end gap-3">
                                    <div class="flex-1">
                                        <x-input-label for="caption" :value="__('Legenda (opcional)')" />
                                        <x-text-input wire:model="caption" id="caption" class="block mt-1 w-full" type="text"
                                                      placeholder="Ex: Vista frontal do equipamento" />
                                    </div>
                                    <button type="submit"
                                            {{ $photosReady ? '' : 'disabled' }}
                                            class="shrink-0 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 {{ !$photosReady ? 'opacity-50 cursor-not-allowed' : '' }}">
                                        <svg class="w-4 h-4 mr-1.5 -ml-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                        </svg>
                                        {{ __('Salvar Fotos') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Divider --}}
                    <div class="border-t border-gray-200"></div>

                    {{-- Gallery --}}
                    @if($assetPhotos->count() > 0)
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z" />
                                </svg>
                                {{ __('Galeria') }}
                            </h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                {{ $assetPhotos->count() }} {{ $assetPhotos->count() === 1 ? 'foto' : 'fotos' }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                            @foreach($assetPhotos as $photo)
                            <div class="rounded-xl overflow-hidden bg-white border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200
                                        {{ $photo->is_primary ? 'ring-2 ring-indigo-500 ring-offset-2' : '' }}">

                                {{-- Image (click abre lightbox) --}}
                                <div class="relative aspect-square overflow-hidden cursor-pointer"
                                     onclick="window.dispatchEvent(new CustomEvent('open-lightbox', { detail: { index: {{ $loop->index }} } }))">
                                    <img src="{{ Storage::url($photo->url) }}"
                                         alt="{{ $photo->caption ?: 'Foto do ativo' }}"
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                         loading="lazy" />

                                    {{-- Primary Badge --}}
                                    @if($photo->is_primary)
                                    <div class="absolute top-2.5 left-2.5 pointer-events-none">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold bg-indigo-600 text-white rounded-md shadow-lg">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
                                            </svg>
                                            Principal
                                        </span>
                                    </div>
                                    @endif

                                    {{-- Zoom icon --}}
                                    <div class="absolute top-2.5 right-2.5 pointer-events-none">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-black/40 backdrop-blur-sm text-white">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>

                                {{-- Footer: legenda + meta + ações --}}
                                <div class="px-3 py-2.5 border-t border-gray-100 space-y-2"
                                     x-data="{ editing: false, captionText: @js($photo->caption ?? '') }">

                                    {{-- Legenda editavel --}}
                                    <div class="min-h-[1rem]">
                                        <button type="button"
                                                x-show="!editing"
                                                x-on:click="editing = true; $nextTick(() => $refs.captionInput{{ $photo->id }}.focus())"
                                                class="w-full text-left text-xs font-medium text-gray-700 truncate hover:text-indigo-600 transition-colors group/caption flex items-center gap-1"
                                                title="Clique para editar a legenda">
                                            <span class="truncate" x-text="captionText || 'Adicionar legenda...'"></span>
                                            <svg class="w-3 h-3 shrink-0 text-gray-300 group-hover/caption:text-indigo-400 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" />
                                            </svg>
                                        </button>
                                        <div x-show="editing" x-cloak class="flex items-center gap-1">
                                            <input type="text"
                                                   x-ref="captionInput{{ $photo->id }}"
                                                   x-model="captionText"
                                                   x-on:keydown.enter="$wire.updateCaption({{ $photo->id }}, captionText); editing = false"
                                                   x-on:keydown.escape="captionText = @js($photo->caption ?? ''); editing = false"
                                                   class="w-full text-xs border-gray-300 rounded px-1.5 py-0.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                                   placeholder="Digite a legenda..."
                                                   maxlength="255" />
                                            <button type="button"
                                                    x-on:click="$wire.updateCaption({{ $photo->id }}, captionText); editing = false"
                                                    class="shrink-0 p-0.5 text-emerald-600 hover:text-emerald-700" title="Salvar">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                            </button>
                                            <button type="button"
                                                    x-on:click="captionText = @js($photo->caption ?? ''); editing = false"
                                                    class="shrink-0 p-0.5 text-gray-400 hover:text-gray-600" title="Cancelar">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Data e tamanho --}}
                                    <div class="flex items-center gap-1.5 text-xs text-gray-400">
                                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $photo->uploaded_at?->format('d/m/Y H:i') }}
                                        @if($photo->file_size)
                                            <span class="text-gray-300">&middot;</span>
                                            {{ number_format($photo->file_size / 1024, 0) }} KB
                                        @endif
                                    </div>

                                    {{-- Botões de ação --}}
                                    <div class="flex items-center gap-2 pt-1 border-t border-gray-100">
                                        @if(!$photo->is_primary)
                                        <button type="button" wire:click="setPrimary({{ $photo->id }})"
                                                class="flex-1 inline-flex items-center justify-center gap-1 px-2 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                            </svg>
                                            Principal
                                        </button>
                                        @endif
                                        <button type="button"
                                                x-on:click="$dispatch('confirm-delete-photo', { id: {{ $photo->id }} })"
                                                class="{{ $photo->is_primary ? 'flex-1' : '' }} inline-flex items-center justify-center gap-1 px-2 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                            Excluir
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Modal de confirmação de exclusão --}}
                        <div x-data="{ showDeleteModal: false, deletePhotoId: null }"
                             x-on:confirm-delete-photo.window="deletePhotoId = $event.detail.id; showDeleteModal = true">

                            <template x-teleport="body">
                                <div x-show="showDeleteModal"
                                     x-cloak
                                     x-transition:enter="ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     x-on:keydown.escape.window="showDeleteModal = false"
                                     class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0">

                                    {{-- Backdrop --}}
                                    <div class="fixed inset-0 bg-gray-500/75 transition-opacity"
                                         x-on:click="showDeleteModal = false"></div>

                                    {{-- Dialog --}}
                                    <div class="relative bg-white rounded-lg shadow-xl sm:max-w-md sm:mx-auto mt-32 p-6"
                                         x-show="showDeleteModal"
                                         x-transition:enter="ease-out duration-300"
                                         x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                         x-transition:leave="ease-in duration-200"
                                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                         x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95">

                                        <div class="flex items-start gap-4">
                                            <div class="shrink-0 flex items-center justify-center w-10 h-10 rounded-full bg-red-100">
                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900">Excluir foto</h3>
                                                <p class="mt-1 text-sm text-gray-500">Tem certeza que deseja excluir esta foto? Esta acao nao pode ser desfeita.</p>
                                            </div>
                                        </div>

                                        <div class="mt-6 flex justify-end gap-3">
                                            <button type="button"
                                                    x-on:click="showDeleteModal = false"
                                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                                                Cancelar
                                            </button>
                                            <button type="button"
                                                    x-on:click="$wire.deletePhoto(deletePhotoId); showDeleteModal = false"
                                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition">
                                                Sim, excluir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Lightbox Component --}}
                        <x-photo-lightbox :photos="$assetPhotos" />
                    </div>
                    @else
                    {{-- Empty State --}}
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900 mb-1">Nenhuma foto cadastrada</h3>
                        <p class="text-sm text-gray-500">Use o formulario acima para enviar as primeiras fotos deste ativo.</p>
                    </div>
                    @endif
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
