<?php

namespace App\Policies;

use App\Models\User;
use App\Models\gcv_uang_muka;

class gcvUangMukaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Direksi','Kasir 1','Kasir 2']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, gcv_uang_muka $gcv_uang_muka): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Direksi','Kasir 1','Kasir 2'])
            && $user->teams()->where('id', $gcv_uang_muka->team_id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Kasir 1','Kasir 2']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, gcv_uang_muka $gcv_uang_muka): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Kasir 1','Kasir 2'])
            && $user->teams()->where('id', $gcv_uang_muka->team_id)->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, gcv_uang_muka $gcv_uang_muka): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Kasir 1','Kasir 2'])
            && $user->teams()->where('id', $gcv_uang_muka->team_id)->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, gcv_uang_muka $gcv_uang_muka): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Kasir 1','Kasir 2'])
            && $user->teams()->where('id', $gcv_uang_muka->team_id)->exists();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, gcv_uang_muka $gcv_uang_muka): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Kasir 1','Kasir 2'])
            && $user->teams()->where('id', $gcv_uang_muka->team_id)->exists();
    }
}