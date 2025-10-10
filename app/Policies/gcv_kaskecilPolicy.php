<?php

namespace App\Policies;

use App\Models\User;
use App\Models\gcv_kaskecil;

class gcv_kaskecilPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Direksi','Kasir 1','Kasir 2']);
    }

    public function view(User $user, gcv_kaskecil $gcv_kaskecil): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Direksi','Kasir 1','Kasir 2'])
            && $user->teams()->where('id', $gcv_kaskecil->team_id)->exists();
    }

    public function create(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Kasir 1','Kasir 2']);
    }

    public function update(User $user, gcv_kaskecil $gcv_kaskecil): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Kasir 1','Kasir 2'])
            && $user->teams()->where('id', $gcv_kaskecil->team_id)->exists();
    }

    public function delete(User $user, gcv_kaskecil $gcv_kaskecil): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Kasir 1','Kasir 2'])
            && $user->teams()->where('id', $gcv_kaskecil->team_id)->exists();
    }

    public function restore(User $user, gcv_kaskecil $gcv_kaskecil): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Kasir 1','Kasir 2'])
            && $user->teams()->where('id', $gcv_kaskecil->team_id)->exists();
    }

    public function forceDelete(User $user, gcv_kaskecil $gcv_kaskecil): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Kasir 1','Kasir 2'])
            && $user->teams()->where('id', $gcv_kaskecil->team_id)->exists();
    }
}