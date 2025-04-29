<?php

namespace App\Policies;

use App\Models\PCA;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PCAPolicy
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
    public function view(User $user, PCA $pCA): bool
    {
        return $user->hasRole(['admin','Marketing','Super admin','Direksi','KPR Officer','Legal Pajak','Legal officer']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PCA $pCA): bool
    {
        return $user->hasRole(['admin','Super admin','Direksi','KPR Officer','KPR Stok']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PCA $pCA): bool
    {
        return $user->hasRole(['admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PCA $pCA): bool
    {
        return $user->hasRole(['admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PCA $pCA): bool
    {
        return $user->hasRole(['admin']);
    }
}
