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
        // Super Admin bisa lihat semua
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Role tertentu bisa lihat stok yang ada di team mereka
        return $user->hasRole([
            'admin','Marketing','Direksi','KPR Officer','Legal Pajak','KPR Stok','Legal officer'
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, gcv_stok $gcv_stok): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole([
            'admin','Marketing','Direksi','','Legal Pajak','Legal officer','KPR Officer'
        ])
        && $user->teams()->where('id', $gcv_stok->team_id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Super admin','Direksi','','KPR Stok']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, gcv_stok $gcv_stok): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Super admin','Direksi','','KPR Stok'])
            && $user->teams()->where('id', $gcv_stok->team_id)->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, gcv_stok $gcv_stok): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Super admin','Direksi','KPR Stok'])
            && $user->teams()->where('id', $gcv_stok->team_id)->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, gcv_stok $gcv_stok): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Super admin','Direksi','KPR Stok'])
            && $user->teams()->where('id', $gcv_stok->team_id)->exists();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, gcv_stok $gcv_stok): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Super admin','Direksi','','KPR Stok'])
            && $user->teams()->where('id', $gcv_stok->team_id)->exists();
    }
}
