<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VerifikasiDajamTkr;

class VerifikasiDajamTkrPolicy
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
    public function view(User $user, VerifikasiDajamTkr $VerifikasiDajamTkr): bool
    {
        return $user->hasRole(['admin','Direksi','Legal Pajak','Legal officer']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin','Legal officer']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VerifikasiDajamTkr $VerifikasiDajamTkr): bool
    {
        return $user->hasRole(['admin','Legal officer']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VerifikasiDajamTkr $VerifikasiDajamTkr): bool
    {
        return $user->hasRole(['admin','Legal officer']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, VerifikasiDajamTkr $VerifikasiDajamTkr): bool
    {
        return $user->hasRole(['admin','Legal Pajak','Legal officer']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, VerifikasiDajamTkr $VerifikasiDajamTkr): bool
    {
        return $user->hasRole(['admin','Legal Pajak','Legal officer']);
    }
}

