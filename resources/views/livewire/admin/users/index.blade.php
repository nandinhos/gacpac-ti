<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Gestão de Usuários') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Gerenciamento completo de usuários, funções e permissões
                </p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-fab-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-fab-blue-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-fab-blue transition ease-in-out duration-150" wire:navigate>
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Novo Usuário') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Mensagens -->
            @if (session()->has('message'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('message') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-700 hover:text-green-900 focus:outline-none rounded-full p-1 hover:bg-green-200 transition-colors">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            @endif
            @if (session()->has('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-red-700 hover:text-red-900 focus:outline-none rounded-full p-1 hover:bg-red-200 transition-colors">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filtros -->
                    <div class="mb-6 space-y-4">
                        <!-- Busca -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input wire:model.live.debounce.300ms="search" type="text" 
                                   placeholder="Buscar por nome, email, ID militar ou posto..." 
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-fab-blue focus:border-fab-blue sm:text-sm">
                        </div>

                        <!-- Filtros Avançados -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            <!-- Filtro Força -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Força</label>
                                <select wire:model.live="filterForce" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-fab-blue focus:border-fab-blue sm:text-sm rounded-md">
                                    <option value="">Todas</option>
                                    @foreach($forces as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro Organização -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Organização</label>
                                <select wire:model.live="filterOrganization" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-fab-blue focus:border-fab-blue sm:text-sm rounded-md">
                                    <option value="">Todas</option>
                                    @foreach($organizations as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro Role -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Função</label>
                                <select wire:model.live="filterRole" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-fab-blue focus:border-fab-blue sm:text-sm rounded-md">
                                    <option value="">Todas</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro Setor -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Setor</label>
                                <select wire:model.live="filterSector" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-fab-blue focus:border-fab-blue sm:text-sm rounded-md">
                                    <option value="">Todos</option>
                                    @foreach($sectors as $sector)
                                        <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select wire:model.live="filterStatus" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-fab-blue focus:border-fab-blue sm:text-sm rounded-md">
                                    <option value="">Todos</option>
                                    <option value="active">Ativo</option>
                                    <option value="inactive">Inativo</option>
                                </select>
                            </div>
                        </div>

                        <!-- Botão Limpar Filtros -->
                        @if($search || $filterForce || $filterOrganization || $filterRole || $filterStatus !== '' || $filterSector)
                            <div class="flex justify-end">
                                <button wire:click="clearFilters" class="text-sm text-fab-blue hover:text-fab-blue-hover underline">
                                    Limpar filtros
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Tabela -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Militar</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organização</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Setor</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Funções</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if ($user->profile_photo_path)
                                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}">
                                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-fab-blue/10 flex items-center justify-center text-fab-blue font-bold">
                                                            {{ substr($user->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                    <div class="text-xs text-gray-400">{{ $user->rank }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 font-mono">{{ $user->military_id ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $user->force }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $user->organization }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->sector)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $user->sector->name }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-sm">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($user->roles as $role)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        {{ ucfirst($role->name) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <button wire:click="toggleStatus({{ $user->id }})" 
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.users.show', $user) }}" class="text-gray-600 hover:text-gray-900" title="Visualizar" wire:navigate>
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user) }}" class="text-fab-blue hover:text-fab-blue-hover" title="Editar" wire:navigate>
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                                </svg>
                                                <p class="text-lg font-medium">Nenhum usuário encontrado</p>
                                                <p class="text-sm text-gray-400 mt-1">Tente ajustar os filtros de busca</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
