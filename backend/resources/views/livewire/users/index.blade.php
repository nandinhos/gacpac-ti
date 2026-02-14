<div class="px-4 sl:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Page header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 font-bold">Usuários</h1>
        </div>

        <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
            <!-- Add User Button -->
            <a href="{{ route('users.create') }}" class="btn bg-fab-blue hover:bg-fab-blue-hover text-white flex items-center gap-2 px-4 py-2 rounded-lg transition-colors shadow-sm" wire:navigate>
                <svg class="w-4 h-4 fill-current opacity-80 shrink-0" viewBox="0 0 16 16">
                    <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                </svg>
                <span class="hidden xs:block ml-2">Novo Usuário</span>
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <!-- Filters -->
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <div class="relative max-w-md w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" 
                       class="block w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:border-fab-blue focus:ring-1 focus:ring-fab-blue transition-shadow" 
                       placeholder="Buscar por nome, posto, ID ou email...">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto w-full divide-y divide-gray-200">
                <thead class="text-xs font-semibold uppercase text-gray-500 bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="font-semibold text-left">Usuário</div>
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="font-semibold text-left">ID Militar</div>
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="font-semibold text-left">Posto/Grad</div>
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="font-semibold text-left">Setor</div>
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="font-semibold text-center">Status</div>
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="font-semibold text-right">Ações</div>
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-200">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap">
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
                                    <div class="font-medium text-gray-800">{{ $user->name }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-gray-600 font-mono text-xs bg-gray-100 px-2 py-1 rounded inline-block">{{ $user->military_id ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-gray-600">{{ $user->rank ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($user->sector)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $user->sector->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic text-xs">Sem setor</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800 border-green-200' : 'bg-red-100 text-red-800 border-red-200' }}">
                                    {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('users.edit', $user) }}" class="p-1.5 text-gray-500 hover:text-fab-blue hover:bg-blue-50 rounded-lg transition-colors" title="Editar" wire:navigate>
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
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
        
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
