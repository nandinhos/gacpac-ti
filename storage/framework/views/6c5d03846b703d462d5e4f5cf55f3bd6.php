<div>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Cautelas')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-md overflow-hidden shadow-sm sm:rounded-lg border border-white/20">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
                        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto flex-1">
                            <div class="flex-1 relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400 group-focus-within:text-fab-blue transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por número, usuário ou ID militar..." class="block w-full pl-10 pr-3 py-2 border-gray-200 rounded-xl bg-white/50 focus:border-fab-blue focus:ring-fab-blue sm:text-sm shadow-sm transition-all">
                            </div>
                            
                            <div class="w-full md:w-48">
                                <select wire:model.live="status" class="w-full border-gray-200 rounded-xl bg-white/50 shadow-sm focus:border-fab-blue focus:ring-fab-blue sm:text-sm">
                                    <option value=""><?php echo e(__('Todos Status')); ?></option>
                                    <option value="open"><?php echo e(__('Aberta')); ?></option>
                                    <option value="closed"><?php echo e(__('Baixada')); ?></option>
                                </select>
                            </div>
                        </div>

                        <a href="<?php echo e(route('custody.create')); ?>" class="inline-flex items-center px-4 py-2 bg-fab-blue border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-fab-blue-hover active:bg-fab-blue-hover focus:outline-none focus:ring-2 focus:ring-fab-blue focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-fab-blue/20 whitespace-nowrap">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <?php echo e(__('Nova Cautela')); ?>

                        </a>
                    </div>

                    <!-- Desktop View -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        <?php echo e(__('Número')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <?php echo e(__('Usuário')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <?php echo e(__('Itens')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <?php echo e(__('Período')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <?php echo e(__('Status')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right">
                                        <?php echo e(__('Ações')); ?>

                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $custodyLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo e($log->cautela_number); ?>

                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="text-sm text-gray-900 font-medium"><?php echo e($log->user->name ?? 'N/A'); ?></div>
                                            <div class="text-xs text-gray-500"><?php echo e($log->user->rank ?? ''); ?></div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="text-sm text-gray-500">
                                                <?php echo e($log->assets->count()); ?> <?php echo e(Str::plural('item', $log->assets->count())); ?>

                                            </div>
                                            <div class="text-xs text-gray-400 truncate max-w-xs">
                                                <?php echo e($log->assets->pluck('name')->join(', ')); ?>

                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?php echo e($log->checkout_date ? $log->checkout_date->format('d/m/Y') : '-'); ?>

                                            </div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->checkin_date): ?>
                                                <div class="text-xs text-gray-500">
                                                    Dev: <?php echo e($log->checkin_date->format('d/m/Y')); ?>

                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->checkin_date): ?>
                                                    <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-gray-100 text-gray-800">
                                                        <?php echo e(__('Baixada')); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-green-100 text-green-800">
                                                        <?php echo e(__('Aberta')); ?>

                                                    </span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->signed_term_url): ?>
                                                    <span title="Documento assinado" class="text-blue-600">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                                    </span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-right">
                                            <div class="flex justify-end space-x-2">
                                                
                                        <a href="<?php echo e(route('custody.show', $log)); ?>" class="text-green-600 hover:text-green-900 transition-colors" wire:navigate title="<?php echo e(__('Detalhes')); ?>">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </a>
                                                
                                                <a href="<?php echo e(route('custody.print', $log)); ?>" class="text-gray-600 hover:text-gray-900" target="_blank" title="<?php echo e(__('Imprimir')); ?>">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                </a>
                                                
                                                <button wire:click="delete(<?php echo e($log->id); ?>)" wire:confirm="Tem certeza? Isso fará com que os ativos voltem para o status DISPONÍVEL." class="text-red-600 hover:text-red-900" title="<?php echo e(__('Excluir')); ?>">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <tr>
                                        <td colspan="6" class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            <?php echo e(__('Nenhuma cautela encontrada.')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile View (Cards) -->
                    <div class="md:hidden space-y-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $custodyLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-bold text-gray-900"><?php echo e($log->cautela_number); ?></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->checkin_date): ?>
                                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-gray-100 text-gray-800">
                                            <?php echo e(__('Baixada')); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-green-100 text-green-800">
                                            <?php echo e(__('Aberta')); ?>

                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                
                                <div class="mb-2">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($log->user->name ?? 'N/A'); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e($log->user->rank ?? ''); ?></div>
                                </div>

                                <div class="mb-3 text-sm text-gray-600">
                                    <span class="font-medium"><?php echo e($log->assets->count()); ?> itens:</span> 
                                    <span class="text-gray-500 truncate block"><?php echo e($log->assets->pluck('name')->join(', ')); ?></span>
                                </div>

                                <div class="flex justify-between items-end mt-4 pt-3 border-t border-gray-200">
                                    <div class="text-xs text-gray-500">
                                        <div>Saída: <?php echo e($log->checkout_date ? $log->checkout_date->format('d/m/Y') : '-'); ?></div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->checkin_date): ?>
                                            <div>Dev: <?php echo e($log->checkin_date->format('d/m/Y')); ?></div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div class="flex justify-end space-x-2">
                                        <a href="<?php echo e(route('custody.show', $log)); ?>" class="text-green-600 hover:text-green-900 transition-colors" wire:navigate title="<?php echo e(__('Detalhes')); ?>">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="<?php echo e(route('custody.print', $log)); ?>" class="text-gray-600 hover:text-gray-900" target="_blank" title="<?php echo e(__('Imprimir')); ?>">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        </a>
                                        <button wire:click="delete(<?php echo e($log->id); ?>)" wire:confirm="Tem certeza?" class="text-red-600 hover:text-red-900" title="<?php echo e(__('Excluir')); ?>">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <div class="text-center py-4 text-gray-500">
                                <?php echo e(__('Nenhuma cautela encontrada.')); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="mt-4">
                        <?php echo e($custodyLogs->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /var/www/html/resources/views/livewire/custody/index.blade.php ENDPATH**/ ?>