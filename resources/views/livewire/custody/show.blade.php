<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center">
            <svg class="w-6 h-6 mr-2 text-fab-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            {{ __('Cautela') }}: {{ $custodyLog->cautela_number }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <a href="javascript:history.back()" class="inline-flex items-center text-sm font-medium text-fab-blue hover:text-fab-blue-hover transition-colors group">
                    <svg class="w-5 h-5 mr-1 transform transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ __('Voltar') }}
                </a>

                <a href="{{ route('custody.print', $custodyLog) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition shadow-lg shadow-red-600/20">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    {{ __('Imprimir PDF') }}
                </a>
            </div>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        {{-- Informações Principais --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            {{-- Header com Status --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <h3 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Nº da Cautela') }}</h3>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 font-mono">{{ $custodyLog->cautela_number }}</p>
                    </div>
                    <div>
                        <h3 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Status') }}</h3>
                        @if($custodyLog->checkin_date)
                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                {{ __('Baixada') }}
                            </span>
                        @else
                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                {{ __('Aberta') }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Data de Abertura') }}</h3>
                        <p class="text-gray-900 dark:text-gray-100">{{ $custodyLog->checkout_date ? $custodyLog->checkout_date->format('d/m/Y') : '-' }}</p>
                    </div>
                    @if($custodyLog->checkin_date)
                    <div>
                        <h3 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Data de Devolução') }}</h3>
                        <p class="text-gray-900 dark:text-gray-100">{{ $custodyLog->checkin_date->format('d/m/Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Militar Responsável --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">{{ __('Militar Responsável') }}</h3>
                @if($custodyLog->user)
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 dark:text-blue-300 font-semibold text-lg">{{ substr($custodyLog->user->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $custodyLog->user->rank }} {{ $custodyLog->user->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $custodyLog->user->military_id }} | {{ $custodyLog->user->email ?? 'Sem email' }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">{{ __('Usuário não encontrado.') }}</p>
                @endif
            </div>

            {{-- Lista de Ativos --}}
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('Ativos Cautelados') }} ({{ $custodyLog->assets->count() }})
                </h3>
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50/50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('QR Code') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Nome') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Nº Série') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Patrimônio') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($custodyLog->assets as $index => $asset)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 font-medium">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-4 py-3 text-sm font-mono text-gray-900 dark:text-gray-100">{{ $asset->qr_code }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $asset->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 font-mono">{{ $asset->serial_number ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $asset->patrimony_number ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        {{ __('Nenhum ativo nesta cautela.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Observações --}}
            @if($custodyLog->notes)
            <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Observações') }}</h3>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $custodyLog->notes }}</div>
            </div>
            @endif

            {{-- Ações --}}
            @if(!$custodyLog->checkin_date)
            <div class="p-6 bg-gray-50 dark:bg-gray-700/50 flex justify-end">
                <button wire:click="openCheckinModal" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Dar Baixa na Cautela') }}
                </button>
            </div>
            @endif
        </div>

        {{-- Documento Assinado --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('Documento Assinado') }}
                </h3>

                @if($custodyLog->signed_term_url)
                    {{-- Documento disponível --}}
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-green-800 dark:text-green-300">{{ __('Documento Assinado Disponível') }}</h4>
                                    <p class="text-sm text-green-600 dark:text-green-400">{{ __('A cautela possui o termo de responsabilidade assinado.') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <span class="font-medium">{{ __('Arquivo:') }}</span> {{ basename($custodyLog->signed_term_url) }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 sm:flex-nowrap">
                                <a href="{{ Storage::url($custodyLog->signed_term_url) }}" target="_blank" class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition flex-1 sm:flex-none">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{ __('Visualizar') }}
                                </a>
                                <button wire:click="downloadSignedDocument" class="inline-flex items-center justify-center px-3 py-2 bg-gray-600 text-white text-xs font-medium rounded hover:bg-gray-700 transition flex-1 sm:flex-none">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    {{ __('Download') }}
                                </button>
                                <button wire:click="openRemoveModal" class="inline-flex items-center justify-center px-3 py-2 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700 transition flex-1 sm:flex-none">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    {{ __('Remover') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Documento pendente --}}
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="w-10 h-10 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">{{ __('Documento Assinado Pendente') }}</h4>
                                    <p class="text-sm text-yellow-600 dark:text-yellow-400">{{ __('O termo de responsabilidade assinado ainda não foi enviado.') }}</p>
                                </div>
                            </div>
                            <button @click="$wire.showUploadModal = true" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition w-full sm:w-auto">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                {{ __('Enviar Documento') }}
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal: Upload Documento --}}
<div x-show="$wire.showUploadModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="$wire.showUploadModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$wire.showUploadModal = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div x-show="$wire.showUploadModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form wire:submit="uploadSignedDocument">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Enviar Documento Assinado') }}</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Arquivo') }} (PDF, JPG, PNG - máx. 10MB)</label>
                        <input type="file" wire:model="signedDocument" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('signedDocument') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Justificativa') }}</label>
                        <textarea wire:model="uploadJustification" rows="3" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm" placeholder="Ex: Documento assinado pelo responsável em 04/02/2026"></textarea>
                        @error('uploadJustification') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Enviar') }}
                    </button>
                    <button type="button" @click="$wire.showUploadModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Cancelar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Remover Documento --}}
<div x-show="$wire.showRemoveModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="$wire.showRemoveModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$wire.showRemoveModal = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div x-show="$wire.showRemoveModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form wire:submit="removeSignedDocument">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Remover Documento Assinado') }}</h3>
                    </div>
                    
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('Tem certeza que deseja remover o documento assinado? Esta ação não pode ser desfeita.') }}</p>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Justificativa para remoção') }}</label>
                        <textarea wire:model="removeJustification" rows="3" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm" placeholder="Ex: Documento enviado incorretamente, necessário reenviar"></textarea>
                        @error('removeJustification') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Remover') }}
                    </button>
                    <button type="button" @click="$wire.showRemoveModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Cancelar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Check-in --}}
<div x-show="$wire.showCheckinModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="$wire.showCheckinModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$wire.showCheckinModal = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div x-show="$wire.showCheckinModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form wire:submit="performCheckin">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Dar Baixa na Cautela') }}</h3>
                    </div>
                    
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        {{ __('Ao dar baixa, todos os ativos serão devolvidos ao almoxarifado e ficarão disponíveis novamente.') }}
                    </p>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Motivo da baixa') }}</label>
                        <textarea wire:model="checkinJustification" rows="3" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm" placeholder="Ex: Devolução programada, transferência de setor, fim de uso..."></textarea>
                        @error('checkinJustification') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Confirmar Baixa') }}
                    </button>
                    <button type="button" @click="$wire.showCheckinModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Cancelar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
