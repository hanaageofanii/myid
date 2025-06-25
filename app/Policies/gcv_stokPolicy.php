<?php

namespace App\Policies;

use App\Models\gcv_stok;
use App\Models\User;

class gcv_stokPolicy
{
   /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin','Marketing','Super admin','Direksi','KPR Officer','Legal Pajak','KPR Stok','Legal officer']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, gcv_stok $gcv_stok): bool
    {
        return $user->hasRole(['admin','Marketing','Super admin','Direksi','KPR Officer','Legal Pajak','Legal officer']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin','KPR Stok']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, gcv_stok $gcv_stok): bool
    {
        return $user->hasRole(['admin','Super admin','Direksi','KPR Officer','KPR Stok']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, gcv_stok $gcv_stok): bool
    {
        return $user->hasRole(['admin','KPR Stok']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, gcv_stok $gcv_stok): bool
    {
        return $user->hasRole(['admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, gcv_stok $gcv_stok): bool
    {
        return $user->hasRole(['admin']);
    }
}