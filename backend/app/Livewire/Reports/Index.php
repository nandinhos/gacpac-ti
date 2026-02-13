<?php

namespace App\Livewire\Reports;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return <<<'HTML'
        <div>
            <x-slot name="header">
                {{ __('Relatórios') }}
            </x-slot>

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Módulo de Relatórios</h3>
                                <p class="mt-1 text-sm text-gray-500">Esta funcionalidade está em desenvolvimento.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }
}
