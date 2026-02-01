
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Custody Log') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form wire:submit="save" class="space-y-6">
                        <!-- Header Info -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="cautela_number" :value="__('Number')" />
                                <x-text-input wire:model="cautela_number" id="cautela_number" class="block mt-1 w-full bg-gray-100" type="text" readonly />
                            </div>

                            <div>
                                <x-input-label for="user_id" :value="__('Military User')" />
                                <select wire:model="user_id" id="user_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">{{ __('Select User') }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->rank }} {{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="checkout_date" :value="__('Checkout Date')" />
                                <x-text-input wire:model="checkout_date" id="checkout_date" class="block mt-1 w-full" type="date" />
                                <x-input-error :messages="$errors->get('checkout_date')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Asset Selection -->
                        <div class="border-t pt-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Select Assets') }}</h3>
                            
                            <div class="mb-4">
                                <x-text-input wire:model.live.debounce.300ms="searchAsset" placeholder="Search available assets..." class="w-full" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-60 overflow-y-auto border p-4 rounded-md">
                                @forelse($availableAssets as $asset)
                                    <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded">
                                        <input type="checkbox" wire:model="selectedAssets" value="{{ $asset->id }}" id="asset_{{ $asset->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <label for="asset_{{ $asset->id }}" class="flex-1 cursor-pointer">
                                            <div class="font-medium">{{ $asset->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $asset->qr_code }} - {{ $asset->serial_number }}</div>
                                        </label>
                                    </div>
                                @empty
                                    <div class="text-gray-500 text-sm text-center col-span-2">{{ __('No available assets found matching your search.') }}</div>
                                @endforelse
                            </div>
                            <x-input-error :messages="$errors->get('selectedAssets')" class="mt-2" />
                            
                            <div class="mt-2 text-sm text-gray-600">
                                {{ count($selectedAssets) }} assets selected.
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="border-t pt-4">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea wire:model="notes" id="notes" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3"></textarea>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Create Custody Log') }}</x-primary-button>
                            <a href="{{ route('custody.index') }}" class="text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
