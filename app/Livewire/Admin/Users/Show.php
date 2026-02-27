<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;

class Show extends Component
{
    public User $user;

    #[Url]
    public $tab = 'profile';

    public function mount(User $user)
    {
        $this->user = $user->load(['sector', 'roles']);
    }

    public function updatedTab($value)
    {
        $this->dispatch('tab-changed', tab: $value);
    }

    public function render()
    {
        // Busca os logs de cautela abertos com seus ativos
        $custodyLogs = $this->user->custodyLogs()
            ->with(['assets.category'])
            ->whereNull('checkin_date')
            ->get();

        // Conta o total de ativos em cautela
        $custodyAssetsCount = $custodyLogs->sum(function ($log) {
            return $log->assets->count();
        });

        // IDs dos ativos em cautela (para excluir da aba Ativos)
        $custodyAssetIds = $custodyLogs->flatMap(function ($log) {
            return $log->assets->pluck('id');
        })->unique()->toArray();

        // Ativos do setor do usuário que NÃO estão em cautela
        $assets = \App\Models\Asset::query()
            ->where('sector_id', $this->user->sector_id)
            ->whereNotIn('id', $custodyAssetIds)
            ->with('category')
            ->get();

        return view('livewire.admin.users.show', [
            'custodyLogs' => $custodyLogs,
            'custodyAssetsCount' => $custodyAssetsCount,
            'assets' => $assets,
            'activeTab' => $this->tab,
        ])->layout('layouts.sgaiti');
    }
}
