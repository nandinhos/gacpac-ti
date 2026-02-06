<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Categoria') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session()->has('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form wire:submit="save" class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Nome <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="name" 
                                   id="name" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   required>
                            @error('name')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Descricao
                            </label>
                            <textarea wire:model="description" 
                                      id="description" 
                                      rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                            @error('description')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="parent_id" class="block text-sm font-medium text-gray-700">
                                    Categoria Pai
                                </label>
                                <select wire:model="parent_id" 
                                        id="parent_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Nenhuma (Categoria Raiz)</option>
                                    @foreach($availableParents as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">
                                    Categorias que criariam ciclos estao ocultas.
                                </p>
                            </div>

                            <div>
                                <label for="color" class="block text-sm font-medium text-gray-700">
                                    Cor
                                </label>
                                <div class="mt-1 flex items-center space-x-3">
                                    <input type="color" 
                                           wire:model="color" 
                                           id="color"
                                           class="h-10 w-20 rounded border-gray-300">
                                    <input type="text" 
                                           wire:model="color" 
                                           class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           placeholder="#3B82F6">
                                </div>
                                @error('color')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-md">
                            <span class="text-sm font-medium text-gray-700">Pre-visualizacao:</span>
                            <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white" 
                                  style="background-color: {{ $color }}">
                                {{ $name ?: 'Nome da Categoria' }}
                            </span>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-md">
                            <h4 class="text-sm font-medium text-blue-900 mb-2">Informacoes da Categoria</h4>
                            <div class="text-xs text-blue-800 space-y-1">
                                <p><strong>Criada em:</strong> {{ $category->created_at->format('d/m/Y H:i') }}</p>
                                @if($category->updated_at != $category->created_at)
                                    <p><strong>Ultima atualizacao:</strong> {{ $category->updated_at->format('d/m/Y H:i') }}</p>
                                @endif
                                <p><strong>Ativos vinculados:</strong> {{ $category->assets()->count() }}</p>
                                @if($category->children()->count() > 0)
                                    <p><strong>Subcategorias:</strong> {{ $category->children()->count() }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t">
                            <button type="button" wire:click="cancel" class="text-gray-600 hover:text-gray-900">
                                {{ __('Cancelar') }}
                            </button>
                            <x-primary-button>
                                {{ __('Salvar Alteracoes') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
