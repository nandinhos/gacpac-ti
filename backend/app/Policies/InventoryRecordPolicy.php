<?php

namespace App\Policies;

use App\Models\InventoryRecord;
use App\Models\MilitaryUser;
use Illuminate\Auth\Access\Response;

class InventoryRecordPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(MilitaryUser $militaryUser): bool
    {
        // Admin e comissão podem ver inventários
        // Usuários regulares não têm acesso a inventários
        return in_array($militaryUser->user_role, ['admin', 'commission']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(MilitaryUser $militaryUser, InventoryRecord $inventoryRecord): bool
    {
        // Admin pode ver todos os inventários
        if ($militaryUser->user_role === 'admin') {
            return true;
        }

        // Comissão pode ver apenas inventários vinculados
        if ($militaryUser->user_role === 'commission') {
            // Verifica se o ID do inventário está na lista de comissões do usuário
            $commissionInventories = $militaryUser->commission_inventories ?? [];
            return in_array($inventoryRecord->id, $commissionInventories);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(MilitaryUser $militaryUser): bool
    {
        // Apenas admin e comissão podem criar inventários
        return in_array($militaryUser->user_role, ['admin', 'commission']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(MilitaryUser $militaryUser, InventoryRecord $inventoryRecord): bool
    {
        // Admin pode atualizar qualquer inventário
        if ($militaryUser->user_role === 'admin') {
            return true;
        }

        // Comissão pode atualizar apenas inventários vinculados
        if ($militaryUser->user_role === 'commission') {
            $commissionInventories = $militaryUser->commission_inventories ?? [];
            return in_array($inventoryRecord->id, $commissionInventories);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(MilitaryUser $militaryUser, InventoryRecord $inventoryRecord): bool
    {
        // Apenas admin pode deletar inventários
        return $militaryUser->user_role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(MilitaryUser $militaryUser, InventoryRecord $inventoryRecord): bool
    {
        // Apenas admin pode restaurar
        return $militaryUser->user_role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(MilitaryUser $militaryUser, InventoryRecord $inventoryRecord): bool
    {
        // Apenas admin pode deletar permanentemente
        return $militaryUser->user_role === 'admin';
    }

    /**
     * Determine whether the user can reopen the inventory.
     */
    public function reopen(MilitaryUser $militaryUser, InventoryRecord $inventoryRecord): bool
    {
        // Admin pode reabrir qualquer inventário
        if ($militaryUser->user_role === 'admin') {
            return true;
        }

        // Comissão pode reabrir apenas inventários vinculados
        if ($militaryUser->user_role === 'commission') {
            $commissionInventories = $militaryUser->commission_inventories ?? [];
            return in_array($inventoryRecord->id, $commissionInventories);
        }

        return false;
    }
}
