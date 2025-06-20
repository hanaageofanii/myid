<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AuditPCA;

class AuditPCAPolicy
{
    /**
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin','Direksi','Legal officer','Legal Pajak']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AuditPCA $AuditPCA): bool
    {
        return $user->hasRole(['admin','Direksi','Legal officer','Legal Pajak']);
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
    public function update(User $user, AuditPCA $AuditPCA): bool
    {
        return $user->hasRole(['admin','Legal officer']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AuditPCA $AuditPCA): bool
    {
        return $user->hasRole(['admin','Legal officer']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AuditPCA $AuditPCA): bool
    {
        return $user->hasRole(['admin','Legal officer']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AuditPCA $AuditPCA): bool
    {
        return $user->hasRole(['admin','Legal officer']);
    }
}
