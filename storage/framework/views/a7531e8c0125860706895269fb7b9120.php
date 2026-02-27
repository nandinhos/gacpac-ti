<div>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Ativos')); ?>

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
                                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar ativos..." class="block w-full pl-10 pr-3 py-2 border-gray-200 rounded-xl bg-white/50 focus:border-fab-blue focus:ring-fab-blue sm:text-sm shadow-sm transition-all">
                            </div>
                        </div>

                        <a href="<?php echo e(route('assets.create')); ?>" class="inline-flex items-center px-4 py-2 bg-fab-blue border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-fab-blue-hover active:bg-fab-blue-hover focus:outline-none focus:ring-2 focus:ring-fab-blue focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-fab-blue/20 whitespace-nowrap">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <?php echo e(__('Novo Ativo')); ?>

                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('name')">
                                        <?php echo e(__('Nome/Modelo')); ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'name'): ?>
                                            <span class="ml-1"><?php echo e($sortDirection === 'asc' ? '↑' : '↓'); ?></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('patrimony_number')">
                                        <?php echo e(__('Ident.')); ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'patrimony_number'): ?>
                                            <span class="ml-1"><?php echo e($sortDirection === 'asc' ? '↑' : '↓'); ?></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('type')">
                                        <?php echo e(__('Tipo')); ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'type'): ?>
                                            <span class="ml-1"><?php echo e($sortDirection === 'asc' ? '↑' : '↓'); ?></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('sector_id')">
                                        <?php echo e(__('Setor')); ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'sector_id'): ?>
                                            <span class="ml-1"><?php echo e($sortDirection === 'asc' ? '↑' : '↓'); ?></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('status')">
                                        <?php echo e(__('Status')); ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'status'): ?>
                                            <span class="ml-1"><?php echo e($sortDirection === 'asc' ? '↑' : '↓'); ?></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        <?php echo e(__('Ações')); ?>

                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="text-sm font-medium text-gray-900"><?php echo e($asset->name); ?></div>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($asset->is_modified): ?>
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-800 border border-orange-200 uppercase" title="Máquina Modificada / Upgrade">
                                                        Mod
                                                    </span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <div class="text-xs text-gray-500"><?php echo e($asset->brand); ?> <?php echo e($asset->model); ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs text-gray-500">SN: <?php echo e($asset->serial_number ?? 'N/A'); ?></div>
                                            <div class="text-xs text-gray-500">Pat: <?php echo e($asset->patrimony_number ?? 'N/A'); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <?php echo e($asset->type ?? $asset->category); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($asset->sector->name ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php
                                                // Normalize status for color lookup (upper case, no accents for safety if needed, but direct map is easier)
                                                // Database has mixed values: "Disponível", "DISPONIVEL", "Em Uso", "Manutenção", etc.
                                                
                                                $status = $asset->status;
                                                $normalizedStatus = strtoupper(Str::slug($status, '_')); // converts "Em Uso" -> "EM_USO", "Manutenção" -> "MANUTENCAO"
                                                
                                                // Map normalized keys to colors
                                                $statusColors = [
                                                    'DISPONIVEL' => 'bg-green-100 text-green-800',
                                                    'EM_USO' => 'bg-blue-100 text-blue-800',
                                                    'MANUTENCAO' => 'bg-orange-100 text-orange-800',
                                                    'BAIXADO' => 'bg-red-800 text-red-100', // Vermelho escuro conforme solicitado
                                                ];

                                                // Fallback to direct match if slug conversion misses something unique, or default
                                                $colorClass = $statusColors[$normalizedStatus] ?? 
                                                              $statusColors[$status] ?? 
                                                              'bg-gray-100 text-gray-800';
                                                
                                                // Label: Use DB value if it looks nice, or format it
                                                // If uppercase like DISPONIVEL, make it Title Case. If mixed like "Em Uso", keep it.
                                                $statusLabel = ctype_upper(str_replace('_', '', $status)) 
                                                    ? str_replace('_', ' ', ucfirst(strtolower($status)))
                                                    : $status;
                                            ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($colorClass); ?>">
                                                <?php echo e($statusLabel); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex justify-end space-x-2">
                                                <a href="<?php echo e(route('assets.edit', $asset)); ?>" class="text-fab-blue hover:text-fab-blue-hover" title="Editar" wire:navigate>
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <button wire:click="confirmDelete(<?php echo e($asset->id); ?>)" class="text-red-600 hover:text-red-900" title="Excluir">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            <?php echo e(__('Nenhum ativo encontrado.')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <?php echo e($assets->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação -->
    <?php if (isset($component)) { $__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirm-modal','data' => ['id' => 'delete-modal','title' => 'Excluir Ativo','message' => 'Tem certeza que deseja excluir este ativo? Esta ação não pode ser desfeita.','confirmText' => 'Excluir','cancelText' => 'Cancelar','confirmColor' => 'red']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirm-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'delete-modal','title' => 'Excluir Ativo','message' => 'Tem certeza que deseja excluir este ativo? Esta ação não pode ser desfeita.','confirmText' => 'Excluir','cancelText' => 'Cancelar','confirmColor' => 'red']); ?>
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
<?php /**PATH /var/www/html/resources/views/livewire/assets/index.blade.php ENDPATH**/ ?>