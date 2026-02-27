<div>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Setores')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-md overflow-hidden shadow-sm sm:rounded-lg border border-white/20">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar setores..." class="border-gray-200 focus:border-fab-blue focus:ring-fab-blue rounded-xl shadow-sm w-full sm:w-1/3 bg-white/50">
                        <a href="<?php echo e(route('sectors.create')); ?>" class="inline-flex items-center px-4 py-2 bg-fab-blue border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-fab-blue-hover active:bg-fab-blue-hover focus:outline-none focus:ring-2 focus:ring-fab-blue focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-fab-blue/20">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <?php echo e(__('Criar Setor')); ?>

                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" x-data="{ activeSector: null }">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <?php echo e(__('Nome')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                        <?php echo e(__('Militares')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <?php echo e(__('Descrição')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <?php echo e(__('Status')); ?>

                                    </th>
                                    <th scope="col" class="relative px-6 py-3 text-right">
                                        <span class="sr-only"><?php echo e(__('Ações')); ?></span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $sectors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sector): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <tr class="hover:bg-gray-50 transition-colors cursor-pointer group" @click="activeSector = (activeSector === <?php echo e($sector->id); ?> ? null : <?php echo e($sector->id); ?>)">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400 transition-transform group-hover:text-indigo-500" :class="{ 'rotate-90': activeSector === <?php echo e($sector->id); ?> }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo e($sector->name); ?>

                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-fab-blue bg-blue-100 rounded-full min-w-[24px]">
                                                <?php echo e($sector->users_count); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs text-gray-500 truncate max-w-xs">
                                                <?php echo e($sector->description); ?>

                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sector->is_active): ?>
                                                <span class="px-2 inline-flex leading-5 font-semibold rounded-full bg-green-50 text-green-700 border border-green-100">
                                                    Ativo
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2 inline-flex leading-5 font-semibold rounded-full bg-red-50 text-red-700 border border-red-100">
                                                    Inativo
                                                </span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium" @click.stop>
                                            <div class="flex justify-end space-x-2">
                                                <a href="<?php echo e(route('sectors.show', $sector)); ?>" class="text-green-600 hover:text-green-900 transition-colors" title="Ver Detalhes do Setor" wire:navigate>
                                                    <svg class="w-5 h-5 transition-transform hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <a href="<?php echo e(route('sectors.edit', $sector)); ?>" class="text-fab-blue hover:text-fab-blue-hover transition-colors" title="Editar" wire:navigate>
                                                    <svg class="w-5 h-5 transition-transform hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <button wire:click="confirmDelete(<?php echo e($sector->id); ?>)" class="text-red-600 hover:text-red-900 transition-colors" title="Excluir">
                                                    <svg class="w-5 h-5 transition-transform hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Linha Expandível (Militares) -->
                                    <tr x-show="activeSector === <?php echo e($sector->id); ?>" x-collapse style="display: none;" class="bg-gray-50/50">
                                        <td colspan="5" class="px-12 py-4">
                                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                <?php echo e(__('Efetivo do Setor')); ?>

                                            </div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sector->users->count() > 0): ?>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $sector->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                        <a href="<?php echo e(route('users.show', $user)); ?>" class="flex items-center p-2 rounded-md hover:bg-white hover:shadow-sm border border-transparent hover:border-gray-200 transition-all group/user" wire:navigate>
                                                            <div class="w-8 h-8 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-500 text-[10px] font-bold uppercase mr-2 group-hover/user:bg-indigo-500 group-hover/user:text-white transition-colors">
                                                                <?php echo e(substr($user->rank ?: 'M', 0, 2)); ?>

                                                            </div>
                                                            <div class="text-xs text-gray-700 font-medium truncate">
                                                                <?php echo e($user->rank); ?> <?php echo e($user->name); ?>

                                                            </div>
                                                        </a>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <p class="text-[11px] text-gray-400 italic"><?php echo e(__('Não há militares ativos vinculados a este setor.')); ?></p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center italic">
                                            <?php echo e(__('Nenhum setor encontrado.')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <?php echo e($sectors->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação -->
    <?php if (isset($component)) { $__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirm-modal','data' => ['id' => 'delete-modal','title' => 'Excluir Setor','message' => 'Tem certeza que deseja excluir este setor? Esta ação não pode ser desfeita.','confirmText' => 'Excluir','cancelText' => 'Cancelar','confirmColor' => 'red']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirm-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'delete-modal','title' => 'Excluir Setor','message' => 'Tem certeza que deseja excluir este setor? Esta ação não pode ser desfeita.','confirmText' => 'Excluir','cancelText' => 'Cancelar','confirmColor' => 'red']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <button
            type="button"
            wire:click="delete"
            class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
        >
            Excluir
        </button>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10)): ?>
<?php $attributes = $__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10; ?>
<?php unset($__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10)): ?>
<?php $component = $__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10; ?>
<?php unset($__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10); ?>
<?php endif; ?>
</div>
<?php /**PATH /var/www/html/resources/views/livewire/sectors/index.blade.php ENDPATH**/ ?>