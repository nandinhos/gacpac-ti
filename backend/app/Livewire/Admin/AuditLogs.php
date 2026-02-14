<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

use Livewire\Attributes\Title;

#[Title('Logs de Auditoria')]
class AuditLogs extends Component
{
    use WithPagination;

    public $search = '';
    public $user_id = '';
    public $model_type = '';
    public $event = '';
    public $date_from = '';
    public $date_to = '';

    public function updatedSearch() { $this->resetPage(); }
    public function updatedUserId() { $this->resetPage(); }
    public function updatedModelType() { $this->resetPage(); }
    public function updatedEvent() { $this->resetPage(); }

    public function render()
    {
        $query = AuditLog::with('user', 'auditable')->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('old_values', 'like', '%' . $this->search . '%')
                  ->orWhere('new_values', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->user_id) {
            $query->where('user_id', $this->user_id);
        }

        if ($this->model_type) {
            $query->where('auditable_type', 'like', '%' . $this->model_type . '%');
        }

        if ($this->event) {
            $query->where('event', $this->event);
        }
        
        if ($this->date_from) {
            $query->whereDate('created_at', '>=', $this->date_from);
        }
        
        if ($this->date_to) {
            $query->whereDate('created_at', '<=', $this->date_to);
        }

        return view('livewire.admin.audit-logs', [
            'logs' => $query->paginate(20),
            'users' => User::orderBy('name')->get(),
            'events' => ['created', 'updated', 'deleted'],
            'models' => [
                'App\Models\Asset' => 'Ativo',
                'App\Models\InventoryRecord' => 'Inventário',
                'App\Models\MaintenanceRecord' => 'Manutenção',
                'App\Models\User' => 'Usuário',
            ],
        ])->layout('layouts.sgaiti');
    }
}
