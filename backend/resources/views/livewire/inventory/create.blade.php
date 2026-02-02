<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Novo Inventário') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form wire:submit="save" class="space-y-6">
                        <!-- Número da Comissão -->
                        <div>
                            <x-input-label for="commission_number" :value="__('Número da Comissão')" />
                            <div class="flex gap-2">
                                <x-text-input
                                    wire:model="commission_number"
                                    id="commission_number"
                                    class="block mt-1 w-full bg-gray-50"
                                    type="text"
                                    required
                                />
                                <button
                                    type="button"
                                    wire:click="generateCommissionNumber"
                                    class="mt-1 px-4 py-2 bg-gray-200 border border-gray-300 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-300 whitespace-nowrap"
                                >
                                    {{ __('Gerar') }}
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('commission_number')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Data de Início -->
                            <div>
                                <x-input-label for="start_date" :value="__('Data de Início')" />
                                <x-text-input
                                    wire:model="start_date"
                                    id="start_date"
                                    class="block mt-1 w-full"
                                    type="date"
                                    required
                                />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                            </div>

                            <!-- Setor -->
                            <div>
                                <x-input-label for="sector_id" :value="__('Setor a Inventariar')" />
                                <select
                                    wire:model="sector_id"
                                    id="sector_id"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required
                                >
                                    <option value="">{{ __('Selecione um setor') }}</option>
                                    @foreach($sectors as $sector)
                                        <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('sector_id')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Responsável -->
                        <div>
                            <x-input-label for="responsible_user_id" :value="__('Militar Responsável')" />
                            <select
                                wire:model="responsible_user_id"
                                id="responsible_user_id"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required
                            >
                                <option value="">{{ __('Selecione o responsável') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->rank }} {{ $user->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('responsible_user_id')" class="mt-2" />
                        </div>

                        <!-- Observações -->
                        <div>
                            <x-input-label for="notes" :value="__('Observações (opcional)')" />
                            <textarea
                                wire:model="notes"
                                id="notes"
                                rows="3"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                placeholder="Instruções ou observações iniciais para a comissão..."
                            ></textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-end gap-4 pt-4 border-t">
                            <a
                                href="{{ route('inventory.index') }}"
                                class="text-gray-600 hover:text-gray-900"
                            >
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button>
                                {{ __('Criar Inventário') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
