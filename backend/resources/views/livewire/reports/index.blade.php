<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Relatórios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Card Relatório de Ativos --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-blue-100 text-fab-blue">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900">Relatório Geral de Ativos</h3>
                    </div>
                    <p class="text-gray-500 text-sm mb-4">
                        Listagem completa de ativos com opções de filtro por categoria e status.
                    </p>
                    
                    <form action="{{ route('reports.assets') }}" method="GET" target="_blank" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Categoria</label>
                            <select name="category_id" wire:model="assetFilters.category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-fab-blue focus:ring focus:ring-fab-blue focus:ring-opacity-50">
                                <option value="">Todas</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" wire:model="assetFilters.status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-fab-blue focus:ring focus:ring-fab-blue focus:ring-opacity-50">
                                <option value="">Todos</option>
                                <option value="active">Ativo</option>
                                <option value="maintenance">Em Manutenção</option>
                                <option value="archived">Arquivado</option>
                            </select>
                        </div>
                        <div class="pt-2">
                             <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-fab-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-fab-blue-hover active:bg-fab-blue-hover focus:outline-none focus:border-fab-blueocus:ring ring-fab-blue disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Gerar PDF
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Card Relatório de Manutenção --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                     <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900">Histórico de Manutenção</h3>
                    </div>
                    <p class="text-gray-500 text-sm mb-4">
                        Relatórios detalhados de manutenções preventivas e corretivas.
                    </p>
                    
                    <form action="{{ route('reports.maintenance') }}" method="GET" target="_blank" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo</label>
                            <select name="type" wire:model="maintenanceFilters.type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-fab-blue focus:ring focus:ring-fab-blue focus:ring-opacity-50">
                                <option value="">Todos</option>
                                <option value="preventive">Preventiva</option>
                                <option value="corrective">Corretiva</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">De</label>
                                <input type="date" name="start_date" wire:model="maintenanceFilters.start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-fab-blue focus:ring focus:ring-fab-blue focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Até</label>
                                <input type="date" name="end_date" wire:model="maintenanceFilters.end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-fab-blue focus:ring focus:ring-fab-blue focus:ring-opacity-50">
                            </div>
                        </div>
                        <div class="pt-2">
                             <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-fab-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-fab-blue-hover active:bg-fab-blue-hover focus:outline-none focus:border-fab-blueocus:ring ring-fab-blue disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Gerar PDF
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Card Termo de Responsabilidade --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                     <div class="flex items-center mb-4">
                         <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900">Termo de Responsabilidade</h3>
                    </div>
                     <p class="text-gray-500 text-sm mb-4">
                        Geração de termos de cautela e responsabilidade para assinatura.
                    </p>
                    
                    <form action="{{ route('reports.term') }}" method="GET" target="_blank" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Responsável</label>
                            <select name="user_id" wire:model="termFilters.user_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-fab-blue focus:ring focus:ring-fab-blue focus:ring-opacity-50">
                                <option value="">Selecione um responsável...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pt-2">
                             <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-fab-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-fab-blue-hover active:bg-fab-blue-hover focus:outline-none focus:border-fab-blueocus:ring ring-fab-blue disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Gerar Termo
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
