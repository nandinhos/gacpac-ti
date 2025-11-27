<?php

namespace App\Policies;

use App\Models\CustodyLog;
use App\Models\MilitaryUser;
use Illuminate\Auth\Access\Response;

class CustodyLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(MilitaryUser $militaryUser): bool
    {
        // Admin pode ver todas
        // Comissão e usuário podem ver suas próprias
        return in_array($militaryUser->user_role, ['admin', 'commission', 'user']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(MilitaryUser $militaryUser, CustodyLog $custodyLog): bool
    {
        // Admin pode ver todas
        if ($militaryUser->user_role === 'admin') {
            return true;
        }

        // Usuário pode ver apenas suas próprias cautelas
        if ($militaryUser->user_role === 'user') {
            return $custodyLog->user_id === $militaryUser->id;
        }

        // Comissão pode ver cautelas relacionadas aos seus inventários
        if ($militaryUser->user_role === 'commission') {
            // Verifica se a cautela pertence a um usuário ou se está relacionada a algum inventário da comissão
            return $custodyLog->user_id === $militaryUser->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(MilitaryUser $militaryUser): bool
    {
        // Apenas admin pode criar cautelas
        return $militaryUser->user_role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(MilitaryUser $militaryUser, CustodyLog $custodyLog): bool
    {
        // Apenas admin pode atualizar cautelas
        return $militaryUser->user_role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(MilitaryUser $militaryUser, CustodyLog $custodyLog): bool
    {
        // Apenas admin pode deletar cautelas
        return $militaryUser->user_role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(MilitaryUser $militaryUser, CustodyLog $custodyLog): bool
    {
        // Apenas admin pode restaurar
        return $militaryUser->user_role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(MilitaryUser $militaryUser, CustodyLog $custodyLog): bool
    {
        // Apenas admin pode deletar permanentemente
        return $militaryUser->user_role === 'admin';
    }
}
