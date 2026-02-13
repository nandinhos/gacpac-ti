<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notificações') }}
            </h2>
            @if(Auth::user()->unreadNotifications->count() > 0)
                <button wire:click="markAllAsRead" 
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Marcar todas como lidas') }}
                </button>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="space-y-4">
                        @forelse($notifications as $notification)
                            <div class="flex items-start p-4 border rounded-lg {{ $notification->read_at ? 'bg-white' : 'bg-blue-50 border-blue-100' }}">
                                <div class="flex-shrink-0 mt-1">
                                    @if(!$notification->read_at)
                                        <div class="h-2 w-2 bg-blue-500 rounded-full"></div>
                                    @else
                                        <div class="h-2 w-2 bg-gray-300 rounded-full"></div>
                                    @endif
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-bold text-gray-900">
                                            {{ $notification->data['title'] ?? 'Notificação' }}
                                        </p>
                                        <span class="text-xs text-gray-500">
                                            {{ $notification->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>
                                    <div class="mt-3 flex space-x-4">
                                        @if(isset($notification->data['action_url']))
                                            <button wire:click="markAsRead('{{ $notification->id }}')" 
                                                    class="text-xs font-semibold text-blue-600 hover:text-blue-800 uppercase tracking-wider">
                                                Ver Detalhes
                                            </button>
                                        @endif
                                        
                                        @if(!$notification->read_at)
                                            <button wire:click="markAsRead('{{ $notification->id }}')" 
                                                    class="text-xs font-semibold text-gray-600 hover:text-gray-800 uppercase tracking-wider">
                                                Marcar como lida
                                            </button>
                                        @endif

                                        <button wire:click="deleteNotification('{{ $notification->id }}')" 
                                                wire:confirm="Excluir esta notificação?"
                                                class="text-xs font-semibold text-red-600 hover:text-red-800 uppercase tracking-wider">
                                            Excluir
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31c.727-.337 1.143-1.056 1.143-1.87v-2.127a3.75 3.75 0 0 1 1.117-2.651l.937-.937c.108-.108.175-.252.175-.411 0-.32-.259-.579-.579-.579H19.5a.75.75 0 0 0 0 1.5h1.168l-.837.837a5.25 5.25 0 0 0-1.581 3.712v2.127c0 .24-.122.453-.332.55a22.348 22.348 0 0 1-4.704 1.13c-.23.03-.456.046-.682.046a2.25 2.25 0 0 1-2.25-2.25V7.5a6 6 0 1 0-12 0v2.25a.75.75 0 0 0 1.5 0V7.5a7.5 7.5 0 0 0-15 0v10.5a2.25 2.25 0 0 0 2.25 2.25c.226 0 .452-.016.682-.046a22.348 22.348 0 0 1 4.704-1.13c.21-.097.332-.31.332-.55V14.414a5.25 5.25 0 0 0 1.581-3.712l.837-.837H19.5a.75.75 0 0 0 0-1.5h-.368c.32 0 .579.259.579.579 0 .159-.067.303-.175.411l-.937.937a3.75 3.75 0 0 1-1.117 2.651v2.127c0 .814-.416 1.533-1.143 1.87a23.848 23.848 0 0 0-5.454 1.31z" />
                                </svg>
                                <p class="mt-4 text-gray-500">Você não possui notificações no momento.</p>
                            </div>
                        @endforelse

                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
