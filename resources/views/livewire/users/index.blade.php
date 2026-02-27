<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Usuários') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-md overflow-hidden shadow-sm sm:rounded-lg border border-white/20">
                <div class="p-6 text-gray-900">
                    <!-- Top Actions: Search & New User -->
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
                        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto flex-1">
                            <div class="flex-1 relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400 group-focus-within:text-fab-blue transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input wire:model.live.debounce.300ms="search" type="text" 
                                       placeholder="Buscar por nome, posto, ID ou email..." 
                                       class="block w-full pl-10 pr-3 py-2 border-gray-200 rounded-xl bg-white/50 focus:border-fab-blue focus:ring-fab-blue sm:text-sm shadow-sm transition-all text-gray-600 placeholder-gray-400">
                            </div>
                        </div>

                        <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-fab-blue border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-fab-blue-hover active:bg-fab-blue-hover focus:outline-none focus:ring-2 focus:ring-fab-blue focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-fab-blue/20 whitespace-nowrap" wire:navigate>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Novo Usuário') }}
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Usuário</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID Militar</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Posto/Grad</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Setor</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 shrink-0 mr-3">
                                                    @if ($user->profile_photo_path)
                                                        <img class="w-10 h-10 rounded-full object-cover border border-gray-200" src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" />
                                                    @else
                                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-fab-blue/10 text-fab-blue font-bold border border-fab-blue/20">
                                                            {{ substr($user->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-gray-600 font-mono text-xs bg-gray-100 px-2 py-1 rounded inline-block">{{ $user->military_id ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-gray-600 text-sm">{{ $user->rank ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->sector)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                                    {{ $user->sector->name }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 italic text-xs">Sem setor</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                                {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('users.edit', $user) }}" class="text-fab-blue hover:text-fab-blue-hover" title="Editar" wire:navigate>
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8">
                                            <div class="flex flex-col items-center justify-center text-center">
                                                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                                    </svg>
                                                </div>
                                                <span class="text-gray-500 text-sm font-medium">Nenhum usuário encontrado</span>
                                                <p class="text-gray-400 text-xs mt-1">Tente ajustar os filtros de busca</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
