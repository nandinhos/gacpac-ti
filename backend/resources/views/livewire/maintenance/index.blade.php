<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Manutenções') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Ativo: <span class="font-medium text-gray-700">{{ $asset->name }}</span>
                    ({{ $asset->qr_code }})
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('assets.edit', $asset) }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition ease-in-out duration-150">
                    {{ __('Voltar ao Ativo') }}
                </a>
                <a href="{{ route('maintenance.create', $asset) }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 transition ease-in-out duration-150">
                    {{ __('Nova Manutenção') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session()->has('message'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Resumo -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                    <div class="p-4">
                        <p class="text-sm font-medium text-gray-600 uppercase">{{ __('Total de Registros') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $records->total() }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                    <div class="p-4">
                        <p class="text-sm font-medium text-gray-600 uppercase">{{ __('Custo Total') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">R$ {{ number_format($totalCost, 2, ',', '.') }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-500">
                    <div class="p-4">
                        <p class="text-sm font-medium text-gray-600 uppercase">{{ __('Próxima Manutenção') }}</p>
                        @php
                            $next = $records->where('next_maintenance_date', '!=', null)
                                ->sortBy('next_maintenance_date')
                                ->first();
                        @endphp
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $next?->next_maintenance_date?->format('d/m/Y') ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="mb-6 flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input wire:model.live.debounce.300ms="search"
                           type="text"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                           placeholder="Buscar por descrição, responsável...">
                </div>
                <div class="w-full md:w-48">
                    <select wire:model.live="typeFilter"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Todos os tipos</option>
                        <option value="preventiva">Preventiva</option>
                        <option value="corretiva">Corretiva</option>
                    </select>
                </div>
            </div>

            <!-- Tabela -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsável</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Custo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Próxima</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($records as $record)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $record->date->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $record->type === 'preventiva' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                                {{ ucfirst($record->type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                            {{ $record->description }}
                                            @if($record->notes)
                                                <p class="text-xs text-gray-500 mt-1 truncate">{{ $record->notes }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $record->performed_by }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                            @if($record->cost)
                                                R$ {{ number_format($record->cost, 2, ',', '.') }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($record->next_maintenance_date)
                                                @if($record->next_maintenance_date->isPast())
                                                    <span class="text-red-600 font-medium">{{ $record->next_maintenance_date->format('d/m/Y') }}</span>
                                                @elseif($record->next_maintenance_date->diffInDays(now()) <= 30)
                                                    <span class="text-yellow-600 font-medium">{{ $record->next_maintenance_date->format('d/m/Y') }}</span>
                                                @else
                                                    <span class="text-gray-600">{{ $record->next_maintenance_date->format('d/m/Y') }}</span>
                                                @endif
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex justify-end space-x-2">
                                                <button wire:click="delete({{ $record->id }})"
                                                        wire:confirm="Tem certeza que deseja excluir este registro de manutenção?"
                                                        class="text-red-600 hover:text-red-900"
                                                        title="Excluir">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            Nenhum registro de manutenção encontrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $records->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
