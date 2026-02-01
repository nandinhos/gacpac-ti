
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Usuário') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form wire:submit="save" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="rank" :value="__('Posto/Gradução')" />
                                <x-text-input wire:model="rank" id="rank" class="block mt-1 w-full" type="text" required />
                                <x-input-error :messages="$errors->get('rank')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="name" :value="__('Nome Completo')" />
                                <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="military_id" :value="__('Identidade Militar')" />
                                <x-text-input wire:model="military_id" id="military_id" class="block mt-1 w-full" type="text" required />
                                <x-input-error :messages="$errors->get('military_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('E-mail')" />
                                <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="sector_id" :value="__('Setor')" />
                                <select wire:model="sector_id" id="sector_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="">{{ __('Selecione um Setor') }}</option>
                                    @foreach($sectors as $sector)
                                        <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('sector_id')" class="mt-2" />
                            </div>
                        </div>

                        <div class="border-t pt-4 mt-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Alterar Senha') }}</h3>
                            <p class="mt-1 text-sm text-gray-600">{{ __('Deixe em branco para manter a senha atual.') }}</p>
                            
                            <div class="mt-4">
                                <x-input-label for="password" :value="__('Nova Senha')" />
                                <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" />
                                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Salvar') }}</x-primary-button>
                            <a href="{{ route('users.index') }}" class="text-gray-600 hover:text-gray-900">{{ __('Cancelar') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
