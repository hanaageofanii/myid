<?php

namespace App\Policies;

use App\Models\User;

class audit_tkr
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
    public function view(User $user, audit_tkr $audit_tkr): bool
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
    public function update(User $user, audit_tkr $audit_tkr): bool
    {
        return $user->hasRole(['admin','Legal officer']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, audit_tkr $audit_tkr): bool
    {
        return $user->hasRole(['admin','Legal officer']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, audit_tkr $audit_tkr): bool
    {
        return $user->hasRole(['admin','Legal officer']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, audit_tkr $audit_tkr): bool
    {
        return $user->hasRole(['admin','Legal officer']);
    }
}
