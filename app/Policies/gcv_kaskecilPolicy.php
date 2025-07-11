<?php

namespace App\Policies;

use App\Models\User;
use App\Models\gcv_kaskecil;

class gcv_kaskecilPolicy
{
    /**
     * Create a new policy instance.
     */
     public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin','Direksi','Kasir 1','Kasir 2']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, gcv_kaskecil $gcv_kaskecil): bool
    {
        return $user->hasRole(['admin','Direksi','Kasir 1','Kasir 2']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin','Kasir 1','Kasir 2']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, gcv_kaskecil $gcv_kaskecil): bool
    {
        return $user->hasRole(['admin','Kasir 1','Kasir 2']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, gcv_kaskecil $gcv_kaskecil): bool
    {
        return $user->hasRole(['admin','Kasir 1','Kasir 2']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, gcv_kaskecil $gcv_kaskecil): bool
    {
        return $user->hasRole(['admin','Kasir 1','Kasir 2']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, gcv_kaskecil $gcv_kaskecil): bool
    {
        return $user->hasRole(['admin','Kasir 1','Kasir 2']);
    }
}
