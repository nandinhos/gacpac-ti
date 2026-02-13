<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Setores') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-md overflow-hidden shadow-sm sm:rounded-lg border border-white/20">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar setores..." class="border-gray-200 focus:border-fab-blue focus:ring-fab-blue rounded-xl shadow-sm w-full sm:w-1/3 bg-white/50">
                        <a href="{{ route('sectors.create') }}" class="inline-flex items-center px-4 py-2 bg-fab-blue border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-fab-blue-hover active:bg-fab-blue-hover focus:outline-none focus:ring-2 focus:ring-fab-blue focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-fab-blue/20">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Criar Setor') }}
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" x-data="{ activeSector: null }">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Nome') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                        {{ __('Militares') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Descrição') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Status') }}
                                    </th>
                                    <th scope="col" class="relative px-6 py-3 text-right">
                                        <span class="sr-only">{{ __('Ações') }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($sectors as $sector)
                                    <tr class="hover:bg-gray-50 transition-colors cursor-pointer group" @click="activeSector = (activeSector === {{ $sector->id }} ? null : {{ $sector->id }})">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400 transition-transform group-hover:text-indigo-500" :class="{ 'rotate-90': activeSector === {{ $sector->id }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $sector->name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-fab-blue bg-blue-100 rounded-full min-w-[24px]">
                                                {{ $sector->military_users_count }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs text-gray-500 truncate max-w-xs">
                                                {{ $sector->description }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs">
                                            @if($sector->is_active)
                                                <span class="px-2 inline-flex leading-5 font-semibold rounded-full bg-green-50 text-green-700 border border-green-100">
                                                    Ativo
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex leading-5 font-semibold rounded-full bg-red-50 text-red-700 border border-red-100">
                                                    Inativo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium" @click.stop>
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('sectors.show', $sector) }}" class="text-green-600 hover:text-green-900 transition-colors" title="Ver Detalhes do Setor" wire:navigate>
                                                    <svg class="w-5 h-5 transition-transform hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('sectors.edit', $sector) }}" class="text-fab-blue hover:text-fab-blue-hover transition-colors" title="Editar" wire:navigate>
                                                    <svg class="w-5 h-5 transition-transform hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <button wire:click="confirmDelete({{ $sector->id }})" class="text-red-600 hover:text-red-900 transition-colors" title="Excluir">
                                                    <svg class="w-5 h-5 transition-transform hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Linha Expandível (Militares) -->
                                    <tr x-show="activeSector === {{ $sector->id }}" x-collapse style="display: none;" class="bg-gray-50/50">
                                        <td colspan="5" class="px-12 py-4">
                                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                {{ __('Efetivo do Setor') }}
                                            </div>
                                            @if($sector->militaryUsers->count() > 0)
                                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                                    @foreach($sector->militaryUsers as $user)
                                                        <a href="{{ route('users.show', $user) }}" class="flex items-center p-2 rounded-md hover:bg-white hover:shadow-sm border border-transparent hover:border-gray-200 transition-all group/user" wire:navigate>
                                                            <div class="w-8 h-8 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-500 text-[10px] font-bold uppercase mr-2 group-hover/user:bg-indigo-500 group-hover/user:text-white transition-colors">
                                                                {{ substr($user->rank ?: 'M', 0, 2) }}
                                                            </div>
                                                            <div class="text-xs text-gray-700 font-medium truncate">
                                                                {{ $user->rank }} {{ $user->name }}
                                                            </div>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-[11px] text-gray-400 italic">{{ __('Não há militares ativos vinculados a este setor.') }}</p>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center italic">
                                            {{ __('Nenhum setor encontrado.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $sectors->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação -->
    <x-confirm-modal
        id="delete-modal"
        title="Excluir Setor"
        message="Tem certeza que deseja excluir este setor? Esta ação não pode ser desfeita."
        confirmText="Excluir"
        cancelText="Cancelar"
        confirmColor="red"
    >
        <button
            type="button"
            wire:click="delete"
            class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
        >
            Excluir
        </button>
    </x-confirm-modal>
</div>
