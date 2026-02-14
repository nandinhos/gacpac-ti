<?php

namespace App\Livewire\Admin\Users;

use App\Models\Sector;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $filterForce = '';

    public $filterOrganization = '';

    public $filterRole = '';

    public $filterStatus = '';

    public $filterSector = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterForce' => ['except' => ''],
        'filterOrganization' => ['except' => ''],
        'filterRole' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterSector' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterForce()
    {
        $this->resetPage();
    }

    public function updatedFilterOrganization()
    {
        $this->resetPage();
    }

    public function updatedFilterRole()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterSector()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filterForce', 'filterOrganization', 'filterRole', 'filterStatus', 'filterSector']);
        $this->resetPage();
    }

    public function toggleStatus($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->id === Auth::id()) {
            session()->flash('error', 'Você não pode desativar seu próprio usuário.');

            return;
        }

        $user->update(['is_active' => ! $user->is_active]);
        session()->flash('message', 'Status do usuário atualizado com sucesso.');
    }

    public function delete($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->id === Auth::id()) {
            session()->flash('error', 'Você não pode excluir seu próprio usuário.');

            return;
        }

        $user->delete();
        session()->flash('message', 'Usuário excluído com sucesso.');
    }

    public function render()
    {
        $users = User::query()
            ->with(['sector', 'roles'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('military_id', 'like', '%'.$this->search.'%')
                        ->orWhere('rank', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->filterForce, function ($query) {
                $query->where('force', $this->filterForce);
            })
            ->when($this->filterOrganization, function ($query) {
                $query->where('organization', $this->filterOrganization);
            })
            ->when($this->filterRole, function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', $this->filterRole);
                });
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('is_active', $this->filterStatus === 'active');
            })
            ->when($this->filterSector, function ($query) {
                $query->where('sector_id', $this->filterSector);
            })
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.users.index', [
            'users' => $users,
            'roles' => Role::where('guard_name', 'web')->get(),
            'sectors' => Sector::orderBy('name')->get(),
            'forces' => ['FAB' => 'FAB', 'EB' => 'EB', 'MB' => 'MB', 'SC' => 'SC'],
            'organizations' => [
                'GAC-PAC' => 'GAC-PAC',
                'ECP-GPX' => 'ECP-GPX',
                'ECP-IJA' => 'ECP-IJA',
                'ECP-POA' => 'ECP-POA',
            ],
        ])->layout('layouts.sgaiti');
    }
}
