
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Painel de Controle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Assets -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="mb-1 text-sm font-medium text-gray-600 uppercase">{{ __('Total de Ativos') }}</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalAssets }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assets in Use -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="mb-1 text-sm font-medium text-gray-600 uppercase">{{ __('Em Uso') }}</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $assetsInUse }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Open Custodies -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="mb-1 text-sm font-medium text-gray-600 uppercase">{{ __('Cautelas Abertas') }}</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $activeCustodies }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Military Users -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-purple-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="mb-1 text-sm font-medium text-gray-600 uppercase">{{ __('Militares') }}</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalMilUsers }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alertas de Manutenção -->
            @if($overdueMaintenances->count() > 0 || $upcomingMaintenances->count() > 0)
            <div class="space-y-4">
                @if($overdueMaintenances->count() > 0)
                <div class="bg-red-50 border-l-4 border-red-500 shadow-sm sm:rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-semibold text-red-800">{{ __('Manutenções Atrasadas') }} ({{ $overdueMaintenances->count() }})</h3>
                            <div class="mt-2 space-y-1">
                                @foreach($overdueMaintenances->take(5) as $m)
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-red-700">
                                        <a href="{{ route('maintenance.index', $m->asset_id) }}" class="hover:underline font-medium">{{ $m->asset?->name ?? 'Ativo removido' }}</a>
                                        — {{ $m->description }}
                                    </span>
                                    <span class="text-red-600 font-medium whitespace-nowrap ml-4">{{ $m->next_maintenance_date->format('d/m/Y') }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($upcomingMaintenances->count() > 0)
                <div class="bg-yellow-50 border-l-4 border-yellow-500 shadow-sm sm:rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-semibold text-yellow-800">{{ __('Manutenções Próximas (30 dias)') }} ({{ $upcomingMaintenances->count() }})</h3>
                            <div class="mt-2 space-y-1">
                                @foreach($upcomingMaintenances->take(5) as $m)
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-yellow-700">
                                        <a href="{{ route('maintenance.index', $m->asset_id) }}" class="hover:underline font-medium">{{ $m->asset?->name ?? 'Ativo removido' }}</a>
                                        — {{ $m->description }}
                                    </span>
                                    <span class="text-yellow-600 font-medium whitespace-nowrap ml-4">{{ $m->next_maintenance_date->format('d/m/Y') }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Recent Custodies Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Cautelas Recentes') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Número') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Militar') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Data') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentCustodies as $custody)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $custody->cautela_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($custody->user)
                                            {{ $custody->user->rank }} {{ $custody->user->name }}
                                        @else
                                            <span class="text-gray-400 italic">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ optional($custody->checkout_date)->format('d/m/Y') ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $custody->checkin_date ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $custody->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">{{ __('Nenhuma cautela recente encontrada.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-gray-50 text-right">
                    <a href="{{ route('custody.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">{{ __('Ver todas as cautelas') }} →</a>
                </div>
            </div>
        </div>
    </div>
