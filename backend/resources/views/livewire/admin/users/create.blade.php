<div>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors" wire:navigate>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Novo Usuário') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Criar novo usuário no sistema
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form wire:submit="save" class="space-y-8">
                        <!-- Seção: Tipo de Usuário -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Tipo de Usuário</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <input wire:model.live="is_military" id="is_military" type="checkbox" class="h-4 w-4 text-fab-blue focus:ring-fab-blue border-gray-300 rounded">
                                    <label for="is_military" class="ml-2 block text-sm font-medium text-gray-700">
                                        Usuário Militar
                                    </label>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Desmarque para usuários civis</p>
                            </div>
                        </div>

                        <!-- Seção: Dados Pessoais -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Dados Pessoais</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nome -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nome Completo <span class="text-red-500">*</span></label>
                                    <input wire:model="name" type="text" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-fab-blue focus:ring-fab-blue sm:text-sm" placeholder="Nome completo do usuário">
                                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                                    <input wire:model="email" type="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-fab-blue focus:ring-fab-blue sm:text-sm" placeholder="email@exemplo.com">
                                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <!-- Força -->
                                <div>
                                    <label for="force" class="block text-sm font-medium text-gray-700">Força <span class="text-red-500">*</span></label>
                                    <select wire:model="force" id="force" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-fab-blue focus:ring-fab-blue sm:text-sm">
                                        @foreach($forces as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('force') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <!-- Posto/Graduação -->
                                <div>
                                    <label for="rank" class="block text-sm font-medium text-gray-700">Posto/Graduação <span class="text-red-500">*</span></label>
                                    <input wire:model="rank" type="text" id="rank" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-fab-blue focus:ring-fab-blue sm:text-sm" placeholder="Ex: Maj, Ten, Sgt">
                                    @error('rank') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <!-- ID Militar -->
                                <div>
                                    <label for="military_id" class="block text-sm font-medium text-gray-700">ID Militar (SARAM) <span class="text-red-500">*</span></label>
                                    <input wire:model="military_id" type="text" id="military_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-fab-blue focus:ring-fab-blue sm:text-sm" placeholder="Número SARAM">
                                    @error('military_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <!-- Organização -->
                                <div>
                                    <label for="organization" class="block text-sm font-medium text-gray-700">Organização <span class="text-red-500">*</span></label>
                                    <select wire:model="organization" id="organization" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-fab-blue focus:ring-fab-blue sm:text-sm">
                                        @foreach($organizations as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('organization') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <!-- Setor -->
                                <div>
                                    <label for="sector_id" class="block text-sm font-medium text-gray-700">Setor</label>
                                    <select wire:model="sector_id" id="sector_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-fab-blue focus:ring-fab-blue sm:text-sm">
                                        <option value="">Selecione um setor...</option>
                                        @foreach($sectors as $sector)
                                            <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('sector_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="is_active" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select wire:model="is_active" id="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-fab-blue focus:ring-fab-blue sm:text-sm">
                                        <option value="1">Ativo</option>
                                        <option value="0">Inativo</option>
                                    </select>
                                    @error('is_active') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Seção: Funções (Roles) -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Funções (Roles) <span class="text-red-500">*</span></h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($roles as $role)
                                    <div class="flex items-center">
                                        <input wire:model="selectedRoles" id="role_{{ $role->id }}" type="checkbox" value="{{ $role->name }}" class="h-4 w-4 text-fab-blue focus:ring-fab-blue border-gray-300 rounded">
                                        <label for="role_{{ $role->id }}" class="ml-2 block text-sm text-gray-700">
                                            {{ ucfirst($role->name) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('selectedRoles') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Seção: Senha -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Senha</h3>
                            
                            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                <div class="flex items-center">
                                    <input wire:model.live="generatePassword" id="generatePassword" type="checkbox" class="h-4 w-4 text-fab-blue focus:ring-fab-blue border-gray-300 rounded">
                                    <label for="generatePassword" class="ml-2 block text-sm font-medium text-gray-700">
                                        Gerar senha automaticamente
                                    </label>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">A senha será exibida após a criação do usuário</p>
                            </div>

                            @if(!$generatePassword)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="customPassword" class="block text-sm font-medium text-gray-700">Senha <span class="text-red-500">*</span></label>
                                        <input wire:model="customPassword" type="password" id="customPassword" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-fab-blue focus:ring-fab-blue sm:text-sm">
                                        @error('customPassword') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="customPasswordConfirmation" class="block text-sm font-medium text-gray-700">Confirmar Senha <span class="text-red-500">*</span></label>
                                        <input wire:model="customPasswordConfirmation" type="password" id="customPasswordConfirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-fab-blue focus:ring-fab-blue sm:text-sm">
                                        @error('customPasswordConfirmation') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-end gap-4 pt-6 border-t">
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" wire:navigate>
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-fab-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-fab-blue-hover focus:bg-fab-blue-hover active:bg-fab-blue focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <span wire:loading.remove wire:target="save">Criar Usuário</span>
                                <span wire:loading wire:target="save">Criando...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
