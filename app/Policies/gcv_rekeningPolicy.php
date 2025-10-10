<?php

namespace App\Policies;

use App\Models\User;
use App\Models\gcv_rekening;

class gcv_rekeningPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin')
            || $user->hasRole(['admin','Direksi','Kasir 1','Kasir 2']);
    }

    public function view(User $user, gcv_rekening $gcv_rekening): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Direksi','Kasir 1','Kasir 2'])
                && $user->teams()->where('id', $gcv_rekening->team_id)->exists());
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin')
            || $user->hasRole(['admin','Kasir 1','Kasir 2']);
    }

    public function update(User $user, gcv_rekening $gcv_rekening): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Kasir 1','Kasir 2'])
                && $user->teams()->where('id', $gcv_rekening->team_id)->exists());
    }

    public function delete(User $user, gcv_rekening $gcv_rekening): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Kasir 1','Kasir 2'])
                && $user->teams()->where('id', $gcv_rekening->team_id)->exists());
    }

    public function restore(User $user, gcv_rekening $gcv_rekening): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Kasir 1','Kasir 2'])
                && $user->teams()->where('id', $gcv_rekening->team_id)->exists());
    }

    public function forceDelete(User $user, gcv_rekening $gcv_rekening): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Kasir 1','Kasir 2'])
                && $user->teams()->where('id', $gcv_rekening->team_id)->exists());
    }
}