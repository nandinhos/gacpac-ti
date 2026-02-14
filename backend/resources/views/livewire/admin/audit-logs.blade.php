<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Logs de Auditoria</h2>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Buscar Conteúdo</label>
                <input wire:model.live.debounce.500ms="search" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Usuário</label>
                <select wire:model.live="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Todos</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Objeto</label>
                <select wire:model.live="model_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Todos</option>
                    @foreach($models as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ação</label>
                <select wire:model.live="event" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Todas</option>
                    @foreach($events as $evt)
                        <option value="{{ $evt }}">{{ ucfirst($evt) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data (De/Até)</label>
                 <div class="flex gap-2">
                    <input wire:model.live="date_from" type="date" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <input wire:model.live="date_to" type="date" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                 </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuário</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ação</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Objeto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alterações</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($logs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $log->user->name ?? 'Sistema/Anônimo' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $log->event === 'created' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $log->event === 'updated' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $log->event === 'deleted' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($log->event) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ class_basename($log->auditable_type) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $log->auditable_id }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs overflow-x-auto">
                            @if($log->event === 'updated')
                                <details class="cursor-pointer text-xs">
                                    <summary class="text-indigo-600 hover:text-indigo-900">Ver Diferenças</summary>
                                    <div class="mt-2 bg-gray-100 dark:bg-gray-900 p-2 rounded">
                                        @foreach($log->new_values as $key => $new)
                                            @if(isset($log->old_values[$key]) && $log->old_values[$key] != $new)
                                                <div class="mb-1">
                                                    <span class="font-bold">{{ $key }}:</span> 
                                                    <span class="text-red-500 line-through">{{ is_array($log->old_values[$key]) ? json_encode($log->old_values[$key]) : $log->old_values[$key] }}</span>
                                                    <span class="text-green-500"> -> {{ is_array($new) ? json_encode($new) : $new }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </details>
                            @else
                                <details class="cursor-pointer text-xs">
                                    <summary class="text-indigo-600 hover:text-indigo-900">Ver Dados</summary>
                                    <pre class="mt-2 bg-gray-100 dark:bg-gray-900 p-2 rounded whitespace-pre-wrap">{{ json_encode($log->new_values ?? $log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </details>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
