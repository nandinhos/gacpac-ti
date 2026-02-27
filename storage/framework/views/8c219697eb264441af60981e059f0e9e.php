<div class="relative ms-3" x-data="{ open: false }">
    <div>
        <button @click="open = !open" 
                class="relative p-2 text-gray-400 hover:text-gray-500 focus:outline-none transition duration-150 ease-in-out">
            <span class="sr-only">Ver notificações</span>
            <svg class="h-6 w-6 transform transition-transform duration-300 hover:scale-110" 
                 :class="<?php echo e($this->unreadCount > 0 ? "'text-fab-blue animate-pulse'" : "''"); ?>"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->unreadCount > 0): ?>
                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full border-2 border-white">
                    <?php echo e($this->unreadCount); ?>

                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->unreadCount > 0): ?>
                <button wire:click="markAllAsRead" class="text-xs text-blue-600 hover:text-blue-800 font-medium">Limpar tudo</button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="max-h-96 overflow-y-auto">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <div wire:click="markAsRead('<?php echo e($notification->id); ?>')" 
                     class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-50 last:border-0 transition duration-150 ease-in-out">
                    <div class="flex items-start">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900"><?php echo e($notification->data['title'] ?? 'Nova Notificação'); ?></p>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2 leading-relaxed"><?php echo e($notification->data['message'] ?? ''); ?></p>
                            <p class="text-[10px] text-gray-400 mt-1.5 font-medium"><?php echo e($notification->created_at->diffForHumans()); ?></p>
                        </div>
                        <div class="ms-2">
                            <div class="h-2 w-2 bg-blue-500 rounded-full"></div>
                        </div>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="px-4 py-8 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="mt-3 text-sm text-gray-500">Nenhuma notificação nova.</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="px-4 py-2 border-t border-gray-100 bg-gray-50 text-center">
            <a href="<?php echo e(route('notifications.index')); ?>" wire:navigate class="text-xs font-semibold text-blue-600 hover:text-blue-800">Ver todas as notificações</a>
        </div>
    </div>
</div>
<?php /**PATH /var/www/html/resources/views/livewire/notifications/dropdown.blade.php ENDPATH**/ ?>