<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PajakTkr;

class PajakTkrPolicy
{
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
    public function view(User $user, PajakTkr $PajakTkr): bool
    {
        return $user->hasRole(['admin','Direksi','Legal officer','Legal Pajak']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin','Legal Pajak']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PajakTkr $PajakTkr): bool
    {
        return $user->hasRole(['admin','Legal Pajak']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PajakTkr $PajakTkr): bool
    {
        return $user->hasRole(['admin','Legal Pajak']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PajakTkr $PajakTkr): bool
    {
        return $user->hasRole(['admin','Legal Pajak']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PajakTkr $PajakTkr): bool
    {
        return $user->hasRole(['admin','Legal Pajak']);
    }
}

