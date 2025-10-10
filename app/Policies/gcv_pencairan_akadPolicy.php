<?php

namespace App\Policies;

use App\Models\User;
use App\Models\gcv_pencairan_akad;

class gcv_pencairan_akadPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin')
            || $user->hasRole(['admin','Direksi','Kasir 1','Kasir 2']);
    }

    public function view(User $user, gcv_pencairan_akad $gcv_pencairan_akad): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Direksi','Kasir 1','Kasir 2'])
                && $user->teams()->where('id', $gcv_pencairan_akad->team_id)->exists());
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin')
            || $user->hasRole(['admin','Kasir 1','Kasir 2']);
    }

    public function update(User $user, gcv_pencairan_akad $gcv_pencairan_akad): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Kasir 1','Kasir 2'])
                && $user->teams()->where('id', $gcv_pencairan_akad->team_id)->exists());
    }

    public function delete(User $user, gcv_pencairan_akad $gcv_pencairan_akad): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Kasir 1','Kasir 2'])
                && $user->teams()->where('id', $gcv_pencairan_akad->team_id)->exists());
    }

    public function restore(User $user, gcv_pencairan_akad $gcv_pencairan_akad): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Kasir 1','Kasir 2'])
                && $user->teams()->where('id', $gcv_pencairan_akad->team_id)->exists());
    }

    public function forceDelete(User $user, gcv_pencairan_akad $gcv_pencairan_akad): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Kasir 1','Kasir 2'])
                && $user->teams()->where('id', $gcv_pencairan_akad->team_id)->exists());
    }
}
