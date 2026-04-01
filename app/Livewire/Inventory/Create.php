<?php

namespace App\Livewire\Inventory;

use App\Models\InventoryRecord;
use App\Models\Sector;
use App\Models\User;
use App\Notifications\InventoryAssignedNotification;
use Livewire\Component;

class Create extends Component
{
    public $commission_number;

    public $start_date;

    public $sector_id = '';

    public $responsible_user_id = '';

    public $notes = '';

    public $is_commission = false;

    public $selected_members = [];

    public function mount()
    {
        $this->start_date = date('Y-m-d');
        $this->generateCommissionNumber();
    }

    public function generateCommissionNumber()
    {
        $year = date('Y');
        $lastId = InventoryRecord::max('id') ?? 0;
        $this->commission_number = 'INV-'.$year.'-'.str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
    }

    public function save()
    {
        $this->validate([
            'commission_number' => 'required|string|max:50|unique:inventory_records,commission_number',
            'start_date' => 'required|date',
            'sector_id' => 'nullable|exists:sectors,id',
            'responsible_user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:2000',
            'selected_members' => $this->is_commission ? 'required|array|min:1' : 'nullable',
        ], [
            'commission_number.required' => 'O número da comissão é obrigatório.',
            'commission_number.unique' => 'Este número de comissão já existe.',
            'start_date.required' => 'A data de início é obrigatória.',
            'responsible_user_id.required' => 'Selecione um responsável.',
            'selected_members.required' => 'Selecione os membros da comissão.',
            'selected_members.min' => 'Selecione pelo menos um membro para a comissão.',
        ]);

        $inventory = InventoryRecord::create([
            'commission_number' => $this->commission_number,
            'start_date' => $this->start_date,
            'sector_id' => $this->sector_id ?: null,
            'responsible_user_id' => $this->responsible_user_id,
            'status' => 'Em Andamento',
            'notes' => $this->notes,
            'is_commission' => $this->is_commission,
        ]);

        if ($this->is_commission && ! empty($this->selected_members)) {
            $inventory->commissionMembers()->attach($this->selected_members);
        }

        // Notificar o responsável (se o sistema de notificações estiver disponível)
        try {
            if ($inventory->responsible_user_id) {
                $responsibleUser = User::find($inventory->responsible_user_id);
                if ($responsibleUser) {
                    $responsibleUser->notify(new InventoryAssignedNotification($inventory));
                }
            }
        } catch (\Exception $e) {
            // Silently fail if notifications are not configured
            \Log::warning('Failed to send inventory notification: '.$e->getMessage());
        }

        session()->flash('message', 'Inventário criado com sucesso.');

        return redirect()->route('inventory.show', $inventory);
    }

    public function render()
    {
        return view('livewire.inventory.create', [
            'sectors' => Sector::where('is_active', true)->orderBy('code')->get(),
            'users' => User::orderBy('name')->get(),
        ])->layout('layouts.sgaiti');
    }
}
