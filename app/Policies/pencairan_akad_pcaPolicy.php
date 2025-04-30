<?php

namespace App\Policies;

use App\Models\User;
use App\Models\pencairan_akad_pca;

class pencairan_akad_pcaPolicy
{
     /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin','Direksi','Kasir 1','Kasir 2']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, pencairan_akad_pca $pencairan_akad_pca): bool
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
    public function update(User $user, pencairan_akad_pca $pencairan_akad_pca): bool
    {
        return $user->hasRole(['admin','Kasir 1','Kasir 2']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, pencairan_akad_pca $pencairan_akad_pca): bool
    {
        return $user->hasRole(['admin','Kasir 1','Kasir 2']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, pencairan_akad_pca $pencairan_akad_pca): bool
    {
        return $user->hasRole(['admin','Kasir 1','Kasir 2']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, pencairan_akad_pca $pencairan_akad_pca): bool
    {
        return $user->hasRole(['admin','Kasir 1','Kasir 2']);
    }
}

