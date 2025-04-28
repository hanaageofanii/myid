<?php

namespace App\Policies;

use App\Models\form_kpr;
use App\Models\User;

class form_kprPolicy
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
    public function view(User $user, form_kpr $form_kpr): bool
    {
        return $user->hasRole(['admin','Direksi','Legal officer','Legal Pajak', 'Kasir 1','Kasir 2','KPR Stok','KPR Officer']);
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
    public function update(User $user, form_kpr $form_kpr): bool
    {
        return $user->hasRole(['admin','KPR Officer','KPR Stok']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, form_kpr $form_kpr): bool
    {
        return $user->hasRole(['admin','KPR Officer']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, form_kpr $form_kpr): bool
    {
        return $user->hasRole(['admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, form_kpr $form_kpr): bool
    {
        return $user->hasRole(['admin']);
    }
}

