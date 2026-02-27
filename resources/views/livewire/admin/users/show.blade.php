<div>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Perfil do Usuário') }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                <span class="font-medium text-gray-700">{{ $user->rank }} {{ $user->name }}</span>
                &middot; {{ $user->email }}
                @if($user->is_active)
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-800 border border-green-200 uppercase tracking-wider">
                        Ativo
                    </span>
                @else
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-800 border border-red-200 uppercase tracking-wider">
                        Inativo
                    </span>
                @endif
            </p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-sm font-medium text-fab-blue hover:text-fab-blue-hover transition-colors group" wire:navigate>
            <svg class="w-5 h-5 mr-1 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('Voltar') }}
        </a>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Mensagens -->
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
                     class="p-4 bg-red-100 border border-red-400 text-red-700 rounded shadow-sm flex items-center justify-between">
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

            <!-- Cabeçalho do Perfil -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            @if ($user->profile_photo_path)
                                <img class="h-24 w-24 rounded-full object-cover" src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}">
                            @else
                                <div class="h-24 w-24 rounded-full bg-fab-blue/10 flex items-center justify-center text-fab-blue text-3xl font-bold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        
                        <!-- Info Principal -->
                        <div class="flex-1">
                            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
                                <h3 class="text-2xl font-bold text-gray-900">{{ $user->rank }} {{ $user->name }}</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500 font-medium">Email:</span>
                                    <span class="ml-1 text-gray-900">{{ $user->email }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 font-medium">ID Militar:</span>
                                    <span class="ml-1 text-gray-900 font-mono">{{ $user->military_id ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 font-medium">Força:</span>
                                    <span class="ml-1 text-gray-900">{{ $user->force }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 font-medium">Organização:</span>
                                    <span class="ml-1 text-gray-900">{{ $user->organization }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 font-medium">Setor:</span>
                                    <span class="ml-1 text-gray-900">{{ $user->sector?->name ?? 'Não vinculado' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 font-medium">Criado em:</span>
                                    <span class="ml-1 text-gray-900">{{ $user->created_at?->format('d/m/Y') ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Ações -->
                        <div class="flex gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-fab-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-fab-blue-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-fab-blue transition ease-in-out duration-150" wire:navigate>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Editar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button wire:click="$set('tab', 'profile')"
                                class="px-6 py-3 border-b-2 font-medium text-sm transition-colors duration-200 {{ $tab === 'profile' ? 'border-fab-blue text-fab-blue' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ __('Perfil') }}
                        </button>

                        <button wire:click="$set('tab', 'assets')"
                                class="px-6 py-3 border-b-2 font-medium text-sm transition-colors duration-200 {{ $tab === 'assets' ? 'border-fab-blue text-fab-blue' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ __('Ativos') }}
                            <span class="ml-1 px-2 py-0.5 text-xs rounded-full {{ $assets->count() > 0 ? 'bg-blue-100 text-fab-blue' : 'bg-gray-100 text-gray-500' }}">
                                {{ $assets->count() }}
                            </span>
                        </button>

                        <button wire:click="$set('tab', 'custody')"
                                class="px-6 py-3 border-b-2 font-medium text-sm transition-colors duration-200 {{ $tab === 'custody' ? 'border-fab-blue text-fab-blue' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            {{ __('Cautelas') }}
                            <span class="ml-1 px-2 py-0.5 text-xs rounded-full {{ $custodyAssetsCount > 0 ? 'bg-blue-100 text-fab-blue' : 'bg-gray-100 text-gray-500' }}">
                                {{ $custodyAssetsCount }}
                            </span>
                        </button>
                    </nav>
                </div>

                <div class="p-6 text-gray-900">
                    <!-- Tab: Perfil -->
                    @if($tab === 'profile')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Informações Pessoais</h4>
                                <dl class="space-y-3">
                                    <div class="flex justify-between border-b border-gray-100 pb-2">
                                        <dt class="text-sm font-medium text-gray-500">Nome Completo</dt>
                                        <dd class="text-sm text-gray-900">{{ $user->name }}</dd>
                                    </div>
                                    <div class="flex justify-between border-b border-gray-100 pb-2">
                                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                                        <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                                    </div>
                                    <div class="flex justify-between border-b border-gray-100 pb-2">
                                        <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                                        <dd class="text-sm text-gray-900">{{ $user->is_military ? 'Militar' : 'Civil' }}</dd>
                                    </div>
                                    <div class="flex justify-between border-b border-gray-100 pb-2">
                                        <dt class="text-sm font-medium text-gray-500">Posto/Graduação</dt>
                                        <dd class="text-sm text-gray-900">{{ $user->rank }}</dd>
                                    </div>
                                    <div class="flex justify-between border-b border-gray-100 pb-2">
                                        <dt class="text-sm font-medium text-gray-500">ID Militar</dt>
                                        <dd class="text-sm text-gray-900 font-mono">{{ $user->military_id ?? 'N/A' }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div>
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Informações Organizacionais</h4>
                                <dl class="space-y-3">
                                    <div class="flex justify-between border-b border-gray-100 pb-2">
                                        <dt class="text-sm font-medium text-gray-500">Força</dt>
                                        <dd class="text-sm text-gray-900">{{ $user->force }}</dd>
                                    </div>
                                    <div class="flex justify-between border-b border-gray-100 pb-2">
                                        <dt class="text-sm font-medium text-gray-500">Organização</dt>
                                        <dd class="text-sm text-gray-900">{{ $user->organization }}</dd>
                                    </div>
                                    <div class="flex justify-between border-b border-gray-100 pb-2">
                                        <dt class="text-sm font-medium text-gray-500">Setor</dt>
                                        <dd class="text-sm text-gray-900">{{ $user->sector?->name ?? 'Não vinculado' }}</dd>
                                    </div>
                                    <div class="flex justify-between border-b border-gray-100 pb-2">
                                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                                        <dd class="text-sm text-gray-900">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div class="flex justify-between border-b border-gray-100 pb-2">
                                        <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                                        <dd class="text-sm text-gray-900">{{ $user->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="md:col-span-2">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Funções e Permissões</h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($user->roles as $role)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @empty
                                        <span class="text-gray-500 text-sm">Nenhuma função atribuída</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Tab: Ativos -->
                    @if($tab === 'assets')
                        @if($assets->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patrimônio</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($assets as $asset)
                                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('assets.edit', $asset) }}'">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-fab-blue hover:text-fab-blue-hover hover:underline">
                                                    {{ $asset->patrimony_number ?? $asset->patrimony_id ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ $asset->description ?? $asset->name ?? 'Sem descrição' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $asset->category?->name ?? $asset->category ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        {{ $asset->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum ativo</h3>
                                <p class="mt-1 text-sm text-gray-500">Este usuário não possui ativos vinculados.</p>
                            </div>
                        @endif
                    @endif

                    <!-- Tab: Cautelas -->
                    @if($tab === 'custody')
                        @if($custodyLogs->count() > 0)
                            <div class="space-y-6">
                                @foreach($custodyLogs as $log)
                                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                                        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-4">
                                                    <span class="text-sm font-medium text-gray-700">Cautela #{{ $log->cautela_number }}</span>
                                                    <span class="text-xs text-gray-500">{{ $log->checkout_date?->format('d/m/Y') ?? 'N/A' }}</span>
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Em Cautela
                                                </span>
                                            </div>
                                        </div>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patrimônio</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($log->assets as $asset)
                                                        <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('assets.edit', $asset) }}'">
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-fab-blue hover:text-fab-blue-hover hover:underline">
                                                                {{ $asset->patrimony_number ?? $asset->patrimony_id ?? 'N/A' }}
                                                            </td>
                                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                                {{ $asset->description ?? $asset->name ?? 'Sem descrição' }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                {{ $asset->category?->name ?? $asset->category ?? 'N/A' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma cautela ativa</h3>
                                <p class="mt-1 text-sm text-gray-500">Este usuário não possui itens sob cautela no momento.</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
