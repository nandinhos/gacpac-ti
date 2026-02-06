<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Fotos do Ativo') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Ativo: <span class="font-medium text-gray-700">{{ $asset->name }}</span>
                    ({{ $asset->qr_code }})
                </p>
            </div>
            <a href="{{ route('assets.edit', $asset) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition ease-in-out duration-150">
                {{ __('Voltar ao Ativo') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session()->has('message'))
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Upload -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Enviar Fotos') }}</h3>

                    <form wire:submit="save" class="space-y-4">
                        <div
                            x-data="{ uploading: false, progress: 0 }"
                            x-on:livewire-upload-start="uploading = true"
                            x-on:livewire-upload-finish="uploading = false"
                            x-on:livewire-upload-cancel="uploading = false"
                            x-on:livewire-upload-error="uploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress"
                        >
                            <div class="flex items-center justify-center w-full">
                                <label for="photos" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="mb-1 text-sm text-gray-500"><span class="font-semibold">Clique para selecionar</span> ou arraste arquivos</p>
                                        <p class="text-xs text-gray-500">JPG, JPEG, PNG ou WEBP (max. 5MB cada)</p>
                                    </div>
                                    <input id="photos" type="file" wire:model="photos" multiple accept="image/jpeg,image/png,image/webp" class="hidden" />
                                </label>
                            </div>

                            <!-- Progress -->
                            <div x-show="uploading" class="mt-2">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" :style="'width: ' + progress + '%'"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1" x-text="'Enviando... ' + progress + '%'"></p>
                            </div>
                        </div>

                        @error('photos.*') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                        <!-- Preview -->
                        @if($photos)
                        <div class="flex flex-wrap gap-3">
                            @foreach($photos as $photo)
                                @if($photo->isPreviewable())
                                <div class="relative">
                                    <img src="{{ $photo->temporaryUrl() }}" class="h-20 w-20 object-cover rounded-lg border" />
                                </div>
                                @endif
                            @endforeach
                        </div>
                        @endif

                        <div class="flex items-end gap-4">
                            <div class="flex-1">
                                <x-input-label for="caption" :value="__('Legenda (opcional)')" />
                                <x-text-input wire:model="caption" id="caption" class="block mt-1 w-full" type="text" placeholder="Ex: Vista frontal do equipamento" />
                            </div>
                            <x-primary-button>{{ __('Enviar') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Galeria -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        {{ __('Galeria') }}
                        <span class="text-sm font-normal text-gray-500">({{ $assetPhotos->count() }} foto(s))</span>
                    </h3>

                    @if($assetPhotos->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($assetPhotos as $photo)
                        <div class="relative group rounded-lg overflow-hidden border {{ $photo->is_primary ? 'ring-2 ring-indigo-500' : '' }}">
                            <a href="{{ Storage::url($photo->url) }}" target="_blank">
                                <img src="{{ Storage::url($photo->url) }}"
                                     alt="{{ $photo->caption }}"
                                     class="w-full h-48 object-cover transition-transform group-hover:scale-105" />
                            </a>

                            @if($photo->is_primary)
                            <div class="absolute top-2 left-2">
                                <span class="px-2 py-1 text-xs font-semibold bg-indigo-600 text-white rounded-full">Principal</span>
                            </div>
                            @endif

                            <!-- Overlay com ações -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all flex items-end justify-center opacity-0 group-hover:opacity-100">
                                <div class="flex gap-2 p-3">
                                    @if(!$photo->is_primary)
                                    <button wire:click="setPrimary({{ $photo->id }})"
                                            class="px-3 py-1 bg-white text-gray-800 text-xs font-medium rounded hover:bg-gray-100 transition"
                                            title="Definir como principal">
                                        Definir Principal
                                    </button>
                                    @endif
                                    <button wire:click="deletePhoto({{ $photo->id }})"
                                            wire:confirm="Tem certeza que deseja excluir esta foto?"
                                            class="px-3 py-1 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700 transition"
                                            title="Excluir">
                                        Excluir
                                    </button>
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="p-2 bg-white">
                                @if($photo->caption)
                                    <p class="text-xs text-gray-700 truncate">{{ $photo->caption }}</p>
                                @endif
                                <p class="text-xs text-gray-400">
                                    {{ $photo->uploaded_at?->format('d/m/Y H:i') }}
                                    @if($photo->file_size)
                                        &middot; {{ number_format($photo->file_size / 1024, 0) }} KB
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Nenhuma foto cadastrada para este ativo.</p>
                        <p class="text-xs text-gray-400">Use o formulário acima para enviar fotos.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
