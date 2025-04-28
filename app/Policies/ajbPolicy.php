<?php

namespace App\Policies;

use App\Models\ajb;
use App\Models\User;

class ajbPolicy
{
     /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin','Direksi','Legal officer','Legal Pajak', 'Kasir 1','Kasir 2','KPR Stok','KPR Officer']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ajb $ajb): bool
    {
        return $user->hasRole(['admin','Direksi','Legal officer','Legal Pajak', 'Kasir 1','Kasir 2','KPR Stok','KPR Officer']);
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
    public function update(User $user, ajb $ajb): bool
    {
        return $user->hasRole(['admin','Legal Pajak']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ajb $ajb): bool
    {
        return $user->hasRole(['admin','Legal Pajak']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ajb $ajb): bool
    {
        return $user->hasRole(['admin','Legal Pajak']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ajb $ajb): bool
    {
        return $user->hasRole(['admin','Legal Pajak']);
    }
}
