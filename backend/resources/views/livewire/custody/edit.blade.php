
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Custody Log') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Status Header -->
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">
                            {{ $cautela_number }}
                            @if($checkin_date)
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ __('CLOSED') }} - {{ $checkin_date }}
                                </span>
                            @else
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ __('OPEN') }}
                                </span>
                            @endif
                        </h3>
                        
                        @if(!$checkin_date)
                            <button wire:click="closeCustody" wire:confirm="Are you sure you want to close this custody log? All assets will be marked as AVAILABLE." class="px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 transition ease-in-out duration-150">
                                {{ __('Receive Assets (Check-in)') }}
                            </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <x-input-label :value="__('Military User')" />
                            <div class="text-gray-900 font-medium mt-1">{{ $user_name }}</div>
                        </div>

                        <div>
                            <x-input-label :value="__('Checkout Date')" />
                            <div class="text-gray-900 font-medium mt-1">{{ $checkout_date }}</div>
                        </div>
                    </div>

                    <!-- Assets List -->
                    <div class="border-t pt-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Assets in Custody') }}</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Name') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('QR Code') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Patrimony') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($assets as $asset)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $asset->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $asset->qr_code }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $asset->patrimony_number }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($notes)
                    <div class="border-t pt-4 mt-6">
                        <x-input-label :value="__('Notes')" />
                        <div class="mt-1 text-gray-700 bg-gray-50 p-3 rounded">{{ $notes }}</div>
                    </div>
                    @endif

                    <div class="mt-6 flex items-center justify-end">
                        <a href="{{ route('custody.index') }}" class="text-gray-600 hover:text-gray-900">{{ __('Back') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
