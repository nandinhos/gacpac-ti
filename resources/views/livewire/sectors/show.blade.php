<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes do Setor') }}: {{ $sector->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 px-4 sm:px-0">
                <a href="javascript:history.back()" class="inline-flex items-center text-sm font-medium text-fab-blue hover:text-fab-blue-hover transition-colors group">
                    <svg class="w-5 h-5 mr-1 transform transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ __('Voltar') }}
                </a>
            </div>
            <!-- Informações do Setor -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 uppercase font-bold">{{ __('Código') }}</p>
                            <p class="text-lg">{{ $sector->code ?: 'N/A' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500 uppercase font-bold">{{ __('Descrição') }}</p>
                            <p class="text-lg">{{ $sector->description ?: __('Sem descrição.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Listagem de Usuários e Ativos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">{{ __('Militares e Equipamentos') }}</h3>

                    <div class="space-y-4" x-data="{ activeUser: null }">
                        @forelse($usersData as $data)
                            @php
                                $user = $data['user'];
                                $sectorAssets = $data['sectorAssets'];
                                $custodyAssets = $data['custodyAssets'];
                                $totalItems = $sectorAssets->count() + $custodyAssets->count();
                            @endphp
                            <div class="border rounded-lg overflow-hidden border-gray-200" wire:key="user-{{ $user->id }}">
                                <!-- Cabeçalho do Usuário -->
                                <div 
                                    @click="activeUser = (activeUser === {{ $user->id }} ? null : {{ $user->id }})"
                                    class="bg-gray-50 p-4 flex justify-between items-center cursor-pointer hover:bg-gray-100 transition-colors"
                                >
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold uppercase">
                                            {{ substr($user->rank ?: 'M', 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center">
                                                <p class="font-bold text-gray-900">{{ $user->rank }} {{ $user->name }}</p>
                                                <a href="{{ route('users.show', $user) }}" class="ml-2 text-indigo-400 hover:text-indigo-600" title="Ver Perfil Completo" @click.stop wire:navigate>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                            </div>
                                            <p class="text-xs text-gray-500">ID: {{ $user->military_id }} | {{ $totalItems }} {{ __('item(ns)') }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <svg 
                                            class="w-5 h-5 text-gray-400 transform transition-transform duration-200" 
                                            :class="{ 'rotate-180': activeUser === {{ $user->id }} }" 
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Conteúdo (Separado) -->
                                <div x-show="activeUser === {{ $user->id }}" x-collapse style="display: none;">
                                    <div class="p-4 bg-white grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Ativos de Setor -->
                                        <div>
                                            <h4 class="text-[10px] font-bold text-indigo-700 uppercase tracking-widest mb-2 border-b pb-1">
                                                {{ __('Ativos do Setor') }}
                                            </h4>
                                            @if($sectorAssets->count() > 0)
                                                @include('livewire.users.partials.asset-table', ['assets' => $sectorAssets])
                                            @else
                                                <p class="text-xs text-gray-400 italic py-2">{{ __('Nenhum ativo de setor.') }}</p>
                                            @endif
                                        </div>

                                        <!-- Itens sob Cautela -->
                                        <div>
                                            <h4 class="text-[10px] font-bold text-orange-700 uppercase tracking-widest mb-2 border-b pb-1">
                                                {{ __('Itens sob Cautela') }}
                                            </h4>
                                            @if($custodyAssets->count() > 0)
                                                @include('livewire.users.partials.asset-table', ['assets' => $custodyAssets, 'isCustody' => true])
                                            @else
                                                <p class="text-xs text-gray-400 italic py-2">{{ __('Nenhuma cautela ativa.') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 border-2 border-dashed border-gray-200 rounded-lg">
                                <p class="text-gray-400">{{ __('Não há militares ativos vinculados a este setor.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
