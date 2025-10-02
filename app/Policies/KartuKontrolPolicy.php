<?php

namespace App\Policies;

use App\Models\kartu_kontrolGCV;
use App\Models\User;

class KartuKontrolPolicy
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
    public function view(User $user, kartu_kontrolGCV $kartu_kontrolGCV): bool
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
    public function update(User $user, kartu_kontrolGCV $kartu_kontrolGCV): bool
    {
        return $user->hasRole(['admin','Kasir 1','Kasir 2']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, kartu_kontrolGCV $kartu_kontrolGCV): bool
    {
        return $user->hasRole(['admin','Kasir 1','Kasir 2']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, kartu_kontrolGCV $kartu_kontrolGCV): bool
    {
        return $user->hasRole(['admin','Kasir 1','Kasir 2']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, kartu_kontrolGCV $kartu_kontrolGCV): bool
    {
        return $user->hasRole(['admin','Kasir 1','Kasir 2']);
    }
}
