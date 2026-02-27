<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nova Categoria') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white/70 backdrop-blur-md shadow-sm sm:rounded-lg border border-white/20">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <p class="text-sm text-gray-500 italic font-semibold uppercase tracking-widest leading-tight">Cadastrar Classificação</p>
                        <a href="{{ route('categories.index') }}" 
                           wire:navigate
                           class="inline-flex items-center px-3 py-1 bg-gray-100 border border-transparent rounded-xl font-bold text-[10px] text-gray-500 uppercase tracking-widest hover:bg-gray-200 transition ease-in-out duration-150">
                            {{ __('Voltar') }}
                        </a>
                    </div>
                    @if (session()->has('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded shadow-sm flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form wire:submit="save" class="space-y-6">
                        <div>
                            <x-input-label for="name" :value="__('Nome da Categoria')" />
                            <x-text-input id="name" type="text" class="mt-1 block w-full" wire:model="name" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Descrição')" />
                            <textarea wire:model="description" 
                                      id="description" 
                                      rows="3"
                                      class="mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:border-fab-blue focus:ring-fab-blue bg-white/50 transition-all"></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="parent_id" :value="__('Categoria Pai')" />
                                <select wire:model="parent_id" 
                                        id="parent_id"
                                        class="mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:border-fab-blue focus:ring-fab-blue bg-white/50 transition-all">
                                    <option value="">Nenhuma (Categoria Raiz)</option>
                                    @foreach($availableParents as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="color" :value="__('Cor de Identificação')" />
                                <div class="mt-1 flex items-center space-x-3">
                                    <input type="color" 
                                           wire:model.live="color" 
                                           id="color"
                                           class="h-10 w-16 rounded cursor-pointer border-gray-300">
                                    <x-text-input type="text" class="flex-1 font-mono uppercase text-sm" wire:model.live="color" placeholder="#HEXCODE" />
                                </div>
                                <x-input-error :messages="$errors->get('color')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50/50 rounded-xl border border-gray-100 flex items-center">
                            <span class="text-xs font-bold uppercase tracking-tighter text-gray-500 mr-4 italic leading-tight">Pré-visualização:</span>
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold shadow-lg shadow-black/5 border border-black/5" 
                                  style="background-color: {{ $color ?: '#E5E7EB' }}; color: {{ $this->getContrastColor($color) }}">
                                {{ $name ?: 'Nome da Categoria' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                            <a href="{{ route('categories.index') }}" 
                               wire:navigate
                               class="text-xs font-bold uppercase text-gray-400 hover:text-gray-600 transition-colors tracking-widest italic">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button class="bg-fab-blue hover:bg-fab-blue-hover active:bg-fab-blue-hover border-transparent rounded-xl font-bold shadow-lg shadow-fab-blue/20">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('Salvar Categoria') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
