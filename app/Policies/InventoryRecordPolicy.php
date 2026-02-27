<?php

namespace App\Policies;

use App\Models\InventoryRecord;
use App\Models\User;

class InventoryRecordPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('inventory.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InventoryRecord $inventoryRecord): bool
    {
        return $user->can('inventory.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('inventory.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, InventoryRecord $inventoryRecord): bool
    {
        // Só permite editar se puder aprovar (admin/operator com permissão)
        return $user->can('inventory.approve') || $user->can('inventory.create');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InventoryRecord $inventoryRecord): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, InventoryRecord $inventoryRecord): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, InventoryRecord $inventoryRecord): bool
    {
        return $user->hasRole('admin');
    }
}
