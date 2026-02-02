<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ativos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex flex-col sm:flex-row gap-4 w-full">
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nome, serial ou patrimônio..." class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full sm:w-1/3">

                            <select wire:model.live="sector_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full sm:w-1/4">
                                <option value="">{{ __('Todos Setores') }}</option>
                                @foreach($sectors as $sector)
                                    <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                                @endforeach
                            </select>

                            <select wire:model.live="type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full sm:w-1/4">
                                <option value="">{{ __('Todos Tipos') }}</option>
                                @foreach($types as $typeOption)
                                    <option value="{{ $typeOption }}">{{ $typeOption }}</option>
                                @endforeach
                            </select>

                            <select wire:model.live="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full sm:w-1/4">
                                <option value="">{{ __('Todos Status') }}</option>
                                @foreach($statuses as $statusOption)
                                    <option value="{{ $statusOption }}">{{ $statusOption }}</option>
                                @endforeach
                            </select>
                        </div>

                        <a href="{{ route('assets.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 whitespace-nowrap">
                            {{ __('Novo Ativo') }}
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" wire:click="sortBy('name')">
                                        {{ __('Nome/Modelo') }}
                                        @if($sortField === 'name')
                                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" wire:click="sortBy('patrimony_number')">
                                        {{ __('Ident.') }}
                                        @if($sortField === 'patrimony_number')
                                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" wire:click="sortBy('type')">
                                        {{ __('Tipo') }}
                                        @if($sortField === 'type')
                                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" wire:click="sortBy('sector_id')">
                                        {{ __('Setor') }}
                                        @if($sortField === 'sector_id')
                                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" wire:click="sortBy('status')">
                                        {{ __('Status') }}
                                        @if($sortField === 'status')
                                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">{{ __('Ações') }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($assets as $asset)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $asset->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $asset->brand }} {{ $asset->model }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs text-gray-500">SN: {{ $asset->serial_number ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">Pat: {{ $asset->patrimony_number ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $asset->type ?? $asset->category }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $asset->sector->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $asset->status === 'DISPONIVEL' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $asset->status === 'EM_USO' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $asset->status === 'MANUTENCAO' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $asset->status === 'BAIXADO' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ $asset->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('assets.edit', $asset) }}" class="text-indigo-600 hover:text-indigo-900 mr-3" wire:navigate>{{ __('Editar') }}</a>
                                            <button wire:click="confirmDelete({{ $asset->id }})" class="text-red-600 hover:text-red-900">{{ __('Excluir') }}</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            {{ __('Nenhum ativo encontrado.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $assets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação -->
    <x-confirm-modal
        id="delete-modal"
        title="Excluir Ativo"
        message="Tem certeza que deseja excluir este ativo? Esta ação não pode ser desfeita."
        confirmText="Excluir"
        cancelText="Cancelar"
        confirmColor="red"
    >
        <button
            type="button"
            wire:click="delete"
            class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
        >
            Excluir
        </button>
    </x-confirm-modal>
</div>
