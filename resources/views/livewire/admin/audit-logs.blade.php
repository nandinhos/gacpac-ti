<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Logs de Auditoria') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-md overflow-hidden shadow-sm sm:rounded-lg border border-white/20">
                
                <!-- Filters -->
                <div class="p-6 border-b border-gray-100 bg-white/50">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div class="lg:col-span-1">
                            <input wire:model.live.debounce.500ms="search" type="text" 
                                   class="block w-full px-3 py-2 border-gray-200 rounded-xl bg-white/50 focus:border-fab-blue focus:ring-fab-blue sm:text-sm shadow-sm transition-all placeholder-gray-400"
                                   placeholder="Buscar Conteúdo...">
                        </div>
                        <div>
                            <select wire:model.live="user_id" class="block w-full px-3 py-2 border-gray-200 rounded-xl bg-white/50 focus:border-fab-blue focus:ring-fab-blue sm:text-sm shadow-sm transition-all text-gray-600">
                                <option value="">Todos os Usuários</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select wire:model.live="model_type" class="block w-full px-3 py-2 border-gray-200 rounded-xl bg-white/50 focus:border-fab-blue focus:ring-fab-blue sm:text-sm shadow-sm transition-all text-gray-600">
                                <option value="">Todos os Objetos</option>
                                @foreach($models as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select wire:model.live="event" class="block w-full px-3 py-2 border-gray-200 rounded-xl bg-white/50 focus:border-fab-blue focus:ring-fab-blue sm:text-sm shadow-sm transition-all text-gray-600">
                                <option value="">Todas as Ações</option>
                                @foreach($events as $evt)
                                    <option value="{{ $evt }}">{{ ucfirst($evt) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                             <div class="flex gap-2">
                                <input wire:model.live="date_from" type="date" class="block w-full px-2 py-2 border-gray-200 rounded-xl bg-white/50 focus:border-fab-blue focus:ring-fab-blue sm:text-sm shadow-sm transition-all text-gray-500" placeholder="De">
                                <input wire:model.live="date_to" type="date" class="block w-full px-2 py-2 border-gray-200 rounded-xl bg-white/50 focus:border-fab-blue focus:ring-fab-blue sm:text-sm shadow-sm transition-all text-gray-500" placeholder="Até">
                             </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Objeto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ação</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alterações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($logs as $log)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                        {{ $log->user->name ?? 'Sistema/Anônimo' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ class_basename($log->auditable_type) }} <span class="text-xs text-gray-400">#{{ $log->auditable_id }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $log->event === 'created' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $log->event === 'updated' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $log->event === 'deleted' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $log->event === 'restored' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                            {{ ucfirst($log->event) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 max-w-lg">
                                        @if($log->event === 'updated')
                                            <details class="group">
                                                <summary class="cursor-pointer text-fab-blue hover:text-fab-blue-hover text-[11px] font-medium focus:outline-none flex items-center gap-1">
                                                    <svg class="w-3 h-3 transition-transform group-open:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Ver Diferenças
                                                </summary>
                                                <div class="mt-1 bg-[#1e1e1e] p-2 rounded border border-gray-700 shadow-inner font-mono text-[10px] leading-relaxed">
                                                    @foreach($log->new_values as $key => $new)
                                                        @if(isset($log->old_values[$key]) && $log->old_values[$key] != $new)
                                                            <div class="grid grid-cols-[auto,1fr] gap-x-3 border-b border-gray-800/50 pb-1 mb-1 last:border-0 last:pb-0 last:mb-0">
                                                                <span class="text-[#c678dd] font-semibold whitespace-nowrap">{{ $key }}:</span> 
                                                                <div class="break-all">
                                                                    <span class="text-[#e06c75] line-through decoration-white/20 mr-2">{{ is_array($log->old_values[$key]) ? json_encode($log->old_values[$key]) : $log->old_values[$key] }}</span>
                                                                    <span class="text-[#98c379]">{{ is_array($new) ? json_encode($new) : $new }}</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </details>
                                        @else
                                            <details class="group">
                                                <summary class="cursor-pointer text-fab-blue hover:text-fab-blue-hover text-[11px] font-medium focus:outline-none flex items-center gap-1">
                                                    <svg class="w-3 h-3 transition-transform group-open:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Ver Dados
                                                </summary>
                                                <div class="mt-1 bg-[#1e1e1e] p-2 rounded border border-gray-700 shadow-inner font-mono text-[10px] leading-relaxed text-[#abb2bf] break-all">
                                                    @foreach($log->new_values ?? $log->old_values as $key => $value)
                                                        <div class="grid grid-cols-[auto,1fr] gap-x-3">
                                                            <span class="text-[#c678dd] font-semibold whitespace-nowrap">{{ $key }}:</span>
                                                            <span class="text-[#98c379]">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </details>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8">
                                        <div class="flex flex-col items-center justify-center text-center">
                                            <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <span class="text-gray-500 text-sm font-medium">Nenhum registro de auditoria encontrado</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
