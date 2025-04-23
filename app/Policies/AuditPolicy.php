<?php

namespace App\Policies;

use App\Models\Audit;
use App\Models\User;

class AuditPolicy
{
    /**
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin','Super admin','Direksi','Staff','Staff Legal','Staff KPR','Legal officer']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Audit $Audit): bool
    {
        return $user->hasRole(['admin','Super admin','Direksi','Legal officer']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'Legal officer']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Audit $Audit): bool
    {
        return $user->hasRole(['admin','Legal officer']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Audit $Audit): bool
    {
        return $user->hasRole(['admin','Legal officer']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Audit $Audit): bool
    {
        return $user->hasRole(['admin','Legal officer']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Audit $Audit): bool
    {
        return $user->hasRole(['admin','Legal officer']);
    }
}
