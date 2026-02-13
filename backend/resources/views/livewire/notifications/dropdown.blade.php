<div class="relative ms-3" x-data="{ open: false }">
    <div>
        <button @click="open = !open" 
                class="relative p-2 text-gray-400 hover:text-gray-500 focus:outline-none transition duration-150 ease-in-out">
            <span class="sr-only">Ver notificações</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31c.727-.337 1.143-1.056 1.143-1.87v-2.127a3.75 3.75 0 0 1 1.117-2.651l.937-.937c.108-.108.175-.252.175-.411 0-.32-.259-.579-.579-.579H19.5a.75.75 0 0 0 0 1.5h1.168l-.837.837a5.25 5.25 0 0 0-1.581 3.712v2.127c0 .24-.122.453-.332.55a22.348 22.348 0 0 1-4.704 1.13c-.23.03-.456.046-.682.046a2.25 2.25 0 0 1-2.25-2.25V7.5a6 6 0 1 0-12 0v2.25a.75.75 0 0 0 1.5 0V7.5a7.5 7.5 0 0 0-15 0v10.5a2.25 2.25 0 0 0 2.25 2.25c.226 0 .452-.016.682-.046a22.348 22.348 0 0 1 4.704-1.13c.21-.097.332-.31.332-.55V14.414a5.25 5.25 0 0 0 1.581-3.712l.837-.837H19.5a.75.75 0 0 0 0-1.5h-.368c.32 0 .579.259.579.579 0 .159-.067.303-.175.411l-.937.937a3.75 3.75 0 0 1-1.117 2.651v2.127c0 .814-.416 1.533-1.143 1.87a23.848 23.848 0 0 0-5.454 1.31z" />
            </svg>
            
            @if($this->unreadCount > 0)
                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full border-2 border-white">
                    {{ $this->unreadCount }}
                </span>
            @endif
        </button>
    </div>

    <!-- Dropdown Panel -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 z-50 mt-2 w-80 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
         style="display: none;">
        
        <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Notificações</span>
            @if($this->unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs text-blue-600 hover:text-blue-800 font-medium">Limpar tudo</button>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse($this->unreadNotifications as $notification)
                <div wire:click="markAsRead('{{ $notification->id }}')" 
                     class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-50 last:border-0 transition duration-150 ease-in-out">
                    <div class="flex items-start">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">{{ $notification->data['title'] ?? 'Nova Notificação' }}</p>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2 leading-relaxed">{{ $notification->data['message'] ?? '' }}</p>
                            <p class="text-[10px] text-gray-400 mt-1.5 font-medium">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="ms-2">
                            <div class="h-2 w-2 bg-blue-500 rounded-full"></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31c.727-.337 1.143-1.056 1.143-1.87v-2.127a3.75 3.75 0 0 1 1.117-2.651l.937-.937c.108-.108.175-.252.175-.411 0-.32-.259-.579-.579-.579H19.5a.75.75 0 0 0 0 1.5h1.168l-.837.837a5.25 5.25 0 0 0-1.581 3.712v2.127c0 .24-.122.453-.332.55a22.348 22.348 0 0 1-4.704 1.13c-.23.03-.456.046-.682.046a2.25 2.25 0 0 1-2.25-2.25V7.5a6 6 0 1 0-12 0v2.25a.75.75 0 0 0 1.5 0V7.5a7.5 7.5 0 0 0-15 0v10.5a2.25 2.25 0 0 0 2.25 2.25c.226 0 .452-.016.682-.046a22.348 22.348 0 0 1 4.704-1.13c.21-.097.332-.31.332-.55V14.414a5.25 5.25 0 0 0 1.581-3.712l.837-.837H19.5a.75.75 0 0 0 0-1.5h-.368c.32 0 .579.259.579.579 0 .159-.067.303-.175.411l-.937.937a3.75 3.75 0 0 1-1.117 2.651v2.127c0 .814-.416 1.533-1.143 1.87a23.848 23.848 0 0 0-5.454 1.31z" />
                    </svg>
                    <p class="mt-3 text-sm text-gray-500">Nenhuma notificação nova.</p>
                </div>
            @endforelse
        </div>

        <div class="px-4 py-2 border-t border-gray-100 bg-gray-50 text-center">
            <a href="{{ route('notifications.index') }}" wire:navigate class="text-xs font-semibold text-blue-600 hover:text-blue-800">Ver todas as notificações</a>
        </div>
    </div>
</div>
