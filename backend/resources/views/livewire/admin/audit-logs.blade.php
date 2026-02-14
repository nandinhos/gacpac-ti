<div class="px-4 sl:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Page header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 font-bold">Logs de Auditoria</h1>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <!-- Filters -->
        <div class="p-5 border-b border-gray-100 bg-white">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-1">
                    <input wire:model.live.debounce.500ms="search" type="text" 
                           class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:border-fab-blue focus:ring-1 focus:ring-fab-blue transition-shadow"
                           placeholder="Buscar Conteúdo...">
                </div>
                <div>
                    <select wire:model.live="user_id" class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-fab-blue focus:ring-1 focus:ring-fab-blue transition-shadow text-gray-600">
                        <option value="">Todos os Usuários</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select wire:model.live="model_type" class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-fab-blue focus:ring-1 focus:ring-fab-blue transition-shadow text-gray-600">
                        <option value="">Todos os Objetos</option>
                        @foreach($models as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select wire:model.live="event" class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-fab-blue focus:ring-1 focus:ring-fab-blue transition-shadow text-gray-600">
                        <option value="">Todas as Ações</option>
                        @foreach($events as $evt)
                            <option value="{{ $evt }}">{{ ucfirst($evt) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                     <div class="flex gap-2">
                        <input wire:model.live="date_from" type="date" class="block w-full px-2 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-fab-blue focus:ring-1 focus:ring-fab-blue transition-shadow text-gray-500" placeholder="De">
                        <input wire:model.live="date_to" type="date" class="block w-full px-2 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-fab-blue focus:ring-1 focus:ring-fab-blue transition-shadow text-gray-500" placeholder="Até">
                     </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto w-full divide-y divide-gray-200">
                <thead class="text-xs font-semibold uppercase text-gray-500 bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="font-semibold text-left">Data</div>
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="font-semibold text-left">Usuário</div>
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="font-semibold text-center">Ação</div>
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="font-semibold text-left">Objeto</div>
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="font-semibold text-left">ID</div>
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="font-semibold text-left">Alterações</div>
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-200">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="font-medium text-gray-800">{{ $log->user->name ?? 'Sistema/Anônimo' }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $log->event === 'created' ? 'bg-green-100 text-green-800 border border-green-200' : '' }}
                                    {{ $log->event === 'updated' ? 'bg-blue-100 text-blue-800 border border-blue-200' : '' }}
                                    {{ $log->event === 'deleted' ? 'bg-red-100 text-red-800 border border-red-200' : '' }}
                                    {{ $log->event === 'restored' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : '' }}">
                                    {{ ucfirst($log->event) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                                {{ class_basename($log->auditable_type) }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap font-mono text-xs text-gray-500">
                                {{ $log->auditable_id }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 max-w-xs">
                                @if($log->event === 'updated')
                                    <details class="group">
                                        <summary class="cursor-pointer text-fab-blue hover:text-fab-blue-hover text-xs font-medium focus:outline-none flex items-center gap-1">
                                            <svg class="w-3 h-3 transition-transform group-open:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                            Ver Diferenças
                                        </summary>
                                        <div class="mt-2 bg-[#1e1e1e] p-3 rounded-lg border border-gray-700 text-xs shadow-inner font-mono overflow-x-auto">
                                            @foreach($log->new_values as $key => $new)
                                                @if(isset($log->old_values[$key]) && $log->old_values[$key] != $new)
                                                    <div class="mb-1 grid grid-cols-[auto,1fr] gap-x-2 border-b border-gray-800 pb-1 last:border-0 last:pb-0">
                                                        <span class="text-sky-400 font-semibold">{{ $key }}:</span> 
                                                        <div class="flex flex-col sm:flex-row sm:gap-2">
                                                            <span class="text-red-400 line-through opacity-70">{{ is_array($log->old_values[$key]) ? json_encode($log->old_values[$key]) : $log->old_values[$key] }}</span>
                                                            <span class="text-emerald-400 font-bold">{{ is_array($new) ? json_encode($new) : $new }}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </details>
                                @else
                                    <details class="group">
                                        <summary class="cursor-pointer text-fab-blue hover:text-fab-blue-hover text-xs font-medium focus:outline-none flex items-center gap-1">
                                            <svg class="w-3 h-3 transition-transform group-open:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                            Ver Dados
                                        </summary>
                                        <pre class="mt-2 bg-[#1e1e1e] text-gray-300 p-3 rounded-lg border border-gray-700 text-xs overflow-auto max-h-60 whitespace-pre-wrap shadow-inner font-mono">{{ json_encode($log->new_values ?? $log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </details>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <span class="text-gray-500 text-sm font-medium">Nenhum registro de auditoria encontrado</span>
                                    <p class="text-gray-400 text-xs mt-1">Tente ajustar os filtros de busca</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $logs->links() }}
        </div>
    </div>
</div>
