<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categorias') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white/70 backdrop-blur-md shadow-sm sm:rounded-lg border border-white/20">
                <div class="p-6 text-gray-900">
                    {{-- Mensagens de feedback --}}
                    @if (session()->has('message'))
                        <div class="mb-6 p-4 bg-green-100/50 backdrop-blur-sm border border-green-200 text-green-700 rounded-xl shadow-sm flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium font-semibold italic">{{ session('message') }}</span>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="mb-6 p-4 bg-red-100/50 backdrop-blur-sm border border-red-200 text-red-700 rounded-xl shadow-sm flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium font-semibold italic">{{ session('error') }}</span>
                        </div>
                    @endif

                    {{-- Barra de Ferramentas (Padrão Imagem 1) --}}
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
                        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto flex-1">
                            <div class="flex-1 relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400 group-focus-within:text-fab-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input wire:model.live.debounce.300ms="search" 
                                       type="text" 
                                       class="block w-full pl-10 pr-3 py-2 border-gray-200 rounded-xl bg-white/50 focus:border-fab-blue focus:ring-fab-blue sm:text-sm shadow-sm transition-all" 
                                       placeholder="Buscar por nome da categoria...">
                            </div>
                            <div class="w-full md:w-64">
                                <select wire:model.live="parentId" 
                                        class="w-full border-gray-200 rounded-xl bg-white/50 shadow-sm focus:border-fab-blue focus:ring-fab-blue sm:text-sm transition-all text-gray-600">
                                    <option value="">Todas as categorias</option>
                                    <option value="root">Somente categorias raiz</option>
                                    @foreach($allCategories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <a href="{{ route('categories.create') }}" 
                           wire:navigate
                           class="inline-flex items-center px-4 py-2 bg-fab-blue border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-fab-blue-hover active:bg-fab-blue-hover focus:outline-none focus:ring-2 focus:ring-fab-blue focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-fab-blue/20 whitespace-nowrap">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Nova Categoria') }}
                        </a>
                    </div>

                    {{-- Tabela (Padrão Imagem 1) --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-400 uppercase bg-gray-50/50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-semibold tracking-wider italic">Categoria / Descrição</th>
                                    <th scope="col" class="px-6 py-3 font-semibold tracking-wider italic">Hierarquia</th>
                                    <th scope="col" class="px-6 py-3 font-semibold tracking-wider italic text-center">Ativos</th>
                                    <th scope="col" class="px-6 py-3 text-right font-semibold tracking-wider italic">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @forelse ($categories as $category)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors" wire:key="category-row-{{ $category->id }}">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                @if($category->color)
                                                    <span class="w-2.5 h-2.5 rounded-full mr-3 shadow-sm" style="background-color: {{ $category->color }}"></span>
                                                @else
                                                    <span class="w-2.5 h-2.5 rounded-full mr-3 bg-gray-200 shadow-sm border border-gray-300"></span>
                                                @endif
                                                <div class="text-sm font-bold text-gray-900">
                                                    {{ $category->name }}
                                                </div>
                                            </div>
                                            @if($category->description)
                                                <div class="text-[11px] text-gray-400 mt-1 pl-5 italic">
                                                    {{ \Illuminate\Support\Str::limit($category->description, 60) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                @if($category->parent)
                                                    <span class="text-[10px] uppercase tracking-widest text-gray-300 font-bold italic">{{ $category->parent->name }}</span>
                                                    <span class="text-sm text-gray-600 flex items-center font-medium">
                                                        <svg class="w-3 h-3 mr-1 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                        {{ $category->name }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-400 border border-gray-200 uppercase tracking-tighter">
                                                        Raiz
                                                    </span>
                                                @endif
                                                
                                                @if($category->children_count > 0)
                                                    <span class="mt-1 text-[10px] text-indigo-400 font-bold italic">
                                                        + {{ $category->children_count }} subcategorias
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-2 py-1 text-xs font-bold rounded-full {{ $category->assets_count > 0 ? 'bg-blue-100 text-blue-800 shadow-sm shadow-blue-100/50' : 'bg-gray-100 text-gray-400' }}">
                                                {{ $category->assets_count }} Ativos
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex justify-end space-x-3">
                                                <a href="{{ route('categories.edit', $category) }}" 
                                                   wire:navigate
                                                   class="text-fab-blue hover:text-fab-blue-hover transition-all transform hover:scale-110"
                                                   title="Editar Categoria">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <button type="button"
                                                        onclick="window.dispatchEvent(new CustomEvent('open-delete-modal', { detail: { id: {{ $category->id }}, name: '{{ $category->name }}' } }))"
                                                        class="text-red-500 hover:text-red-700 transition-all transform hover:scale-110"
                                                        title="Excluir Categoria">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-300 italic">
                                            <svg class="mx-auto h-10 w-10 text-gray-200 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                            <p class="text-sm font-semibold uppercase tracking-widest text-gray-400">Nenhuma categoria encontrada</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($categories->hasPages())
                        <div class="mt-8">
                            {{ $categories->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de Exclusão Alpine.js --}}
    <div x-data="{ showDeleteModal: false, deleteId: null, deleteName: '' }"
         x-on:open-delete-modal.window="deleteId = $event.detail.id; deleteName = $event.detail.name; showDeleteModal = true"
         x-on:close-delete-modal.window="showDeleteModal = false"
         x-on:category-deleted.window="showDeleteModal = false">
        
        <template x-teleport="body">
            <div x-show="showDeleteModal" 
                 class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0"
                 x-cloak>
                {{-- Backdrop --}}
                <div x-show="showDeleteModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transform transition-all" 
                     @click="showDeleteModal = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                {{-- Dialog --}}
                <div x-show="showDeleteModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-lg sm:mx-auto mt-24">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-medium text-gray-900">Excluir Categoria</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Tem certeza que deseja excluir a categoria <span class="font-bold text-gray-700" x-text="deleteName"></span>? Esta ação não poderá ser desfeita e só será permitida se não houver ativos ou subcategorias vinculadas.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="button" 
                                @click="$wire.delete(deleteId)"
                                class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-150">
                            Excluir
                        </button>
                        <button type="button" 
                                @click="showDeleteModal = false"
                                class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-150">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
