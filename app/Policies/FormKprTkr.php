<?php

namespace App\Policies;

use App\Models\User;

class FormKprTkr
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin','Direksi','Legal officer','Legal Pajak','KPR Stok','KPR Officer']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FormKprTkr $FormKprTkr): bool
    {
        return $user->hasRole(['admin','Direksi','Legal officer','Legal Pajak','KPR Stok','KPR Officer']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin','KPR Officer']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FormKprTkr $FormKprTkr): bool
    {
        return $user->hasRole(['admin','KPR Officer']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FormKprTkr $FormKprTkr): bool
    {
        return $user->hasRole(['admin','KPR Officer']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FormKprTkr $FormKprTkr): bool
    {
        return $user->hasRole(['admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FormKprTkr $FormKprTkr): bool
    {
        return $user->hasRole(['admin']);
    }
}

