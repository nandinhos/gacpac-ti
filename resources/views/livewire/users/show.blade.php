<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Perfil do Militar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="javascript:history.back()" class="inline-flex items-center text-sm font-medium text-fab-blue hover:text-fab-blue-hover transition-colors group">
                    <svg class="w-5 h-5 mr-1 transform transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ __('Voltar') }}
                </a>
            </div>
            <!-- Cabeçalho de Perfil -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center space-x-6">
                        <div class="h-24 w-24 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 text-3xl font-bold uppercase">
                            {{ substr($user->rank ?: 'M', 0, 2) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $user->rank }} {{ $user->name }}</h3>
                            <div class="mt-1 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-500 uppercase font-medium">
                                <div><span class="text-gray-400">ID:</span> {{ $user->military_id }}</div>
                                <div><span class="text-gray-400">Setor:</span> {{ $user->sector->name ?? 'N/A' }}</div>
                                <div><span class="text-gray-400">Email:</span> {{ $user->email }}</div>
                            </div>
                        </div>
                        <div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Seção: Ativos de Setor -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-4 text-indigo-700 border-b pb-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h4 class="font-bold uppercase tracking-wider text-sm">{{ __('Ativos Vinculados ao Setor') }}</h4>
                        </div>
                        
                        @if($user->assets->count() > 0)
                            @include('livewire.users.partials.asset-table', ['assets' => $user->assets])
                        @else
                            <p class="text-sm text-gray-400 italic py-4 text-center">{{ __('Nenhum ativo de setor vinculado.') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Seção: Itens sob Cautela -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-4 text-orange-700 border-b pb-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <h4 class="font-bold uppercase tracking-wider text-sm">{{ __('Itens sob Cautela Ativa') }}</h4>
                        </div>

                        @if($custodyAssets->count() > 0)
                            @include('livewire.users.partials.asset-table', ['assets' => $custodyAssets, 'isCustody' => true])
                        @else
                            <p class="text-sm text-gray-400 italic py-4 text-center">{{ __('Nenhum item sob cautela no momento.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
