<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\MilitaryUser;
use Illuminate\Auth\Access\Response;

class AssetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(MilitaryUser $militaryUser): bool
    {
        // Todos os usuários autenticados podem ver a lista de ativos
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(MilitaryUser $militaryUser, Asset $asset): bool
    {
        // Todos podem visualizar detalhes de ativos
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(MilitaryUser $militaryUser): bool
    {
        // Apenas admin e comissão podem criar ativos
        return in_array($militaryUser->user_role, ['admin', 'commission']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(MilitaryUser $militaryUser, Asset $asset): bool
    {
        // Apenas admin e comissão podem atualizar ativos
        return in_array($militaryUser->user_role, ['admin', 'commission']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(MilitaryUser $militaryUser, Asset $asset): bool
    {
        // Apenas admin pode deletar ativos
        return $militaryUser->user_role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(MilitaryUser $militaryUser, Asset $asset): bool
    {
        // Apenas admin pode restaurar
        return $militaryUser->user_role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(MilitaryUser $militaryUser, Asset $asset): bool
    {
        // Apenas admin pode deletar permanentemente
        return $militaryUser->user_role === 'admin';
    }
}
