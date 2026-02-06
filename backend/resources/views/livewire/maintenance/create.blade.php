<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Registrar Manutenção') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Ativo: <span class="font-medium text-gray-700">{{ $asset->name }}</span>
                    ({{ $asset->qr_code }})
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form wire:submit="save" class="space-y-6">
                        <!-- Tipo e Data -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="type" :value="__('Tipo de Manutenção')" />
                                <select wire:model="type" id="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="corretiva">Corretiva</option>
                                    <option value="preventiva">Preventiva</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="date" :value="__('Data da Manutenção')" />
                                <x-text-input wire:model="date" id="date" class="block mt-1 w-full" type="date" required />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Descrição -->
                        <div>
                            <x-input-label for="description" :value="__('Descrição do Serviço')" />
                            <textarea wire:model="description" id="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3" required placeholder="Descreva o serviço realizado..."></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Responsável e Custo -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="performed_by" :value="__('Realizado por')" />
                                <x-text-input wire:model="performed_by" id="performed_by" class="block mt-1 w-full" type="text" required placeholder="Nome do técnico ou empresa" />
                                <x-input-error :messages="$errors->get('performed_by')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="cost" :value="__('Custo (R$)')" />
                                <x-text-input wire:model="cost" id="cost" class="block mt-1 w-full" type="number" step="0.01" min="0" placeholder="0,00" />
                                <x-input-error :messages="$errors->get('cost')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Próxima Manutenção -->
                        <div class="border-t pt-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="next_maintenance_date" :value="__('Próxima Manutenção Agendada')" />
                                    <x-text-input wire:model="next_maintenance_date" id="next_maintenance_date" class="block mt-1 w-full" type="date" />
                                    <p class="text-xs text-gray-500 mt-1">Opcional. Data prevista para a próxima manutenção.</p>
                                    <x-input-error :messages="$errors->get('next_maintenance_date')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Observações -->
                        <div>
                            <x-input-label for="notes" :value="__('Observações')" />
                            <textarea wire:model="notes" id="notes" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="2" placeholder="Observações adicionais (opcional)"></textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t">
                            <a href="{{ route('maintenance.index', $asset) }}" class="text-gray-600 hover:text-gray-900">{{ __('Cancelar') }}</a>
                            <x-primary-button>{{ __('Registrar Manutenção') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
