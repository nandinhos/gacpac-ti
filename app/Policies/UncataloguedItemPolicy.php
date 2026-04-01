<?php

namespace App\Policies;

use App\Models\UncataloguedItem;
use App\Models\User;

class UncataloguedItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('inventory.view');
    }

    public function view(User $user, UncataloguedItem $item): bool
    {
        return $user->can('inventory.view');
    }

    public function create(User $user): bool
    {
        return $user->can('inventory.create');
    }

    public function update(User $user, UncataloguedItem $item): bool
    {
        if ($user->can('inventory.approve')) {
            return true;
        }

        return $item->created_by_user_id === $user->id;
    }

    public function delete(User $user, UncataloguedItem $item): bool
    {
        if ($user->can('inventory.approve')) {
            return true;
        }

        return $item->created_by_user_id === $user->id;
    }
}
