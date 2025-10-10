<?php

namespace App\Policies;

use App\Models\gcv_kpr;
use App\Models\User;

class gcv_kprPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole([
            'admin','Direksi','Legal officer','Legal Pajak',
            'KPR Stok','KPR Officer','Lapangan','Kasir 1','Kasir 2'
        ]);
    }

    public function view(User $user, gcv_kpr $gcv_kpr): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole([
            'admin','Direksi','Legal officer','Legal Pajak',
            'KPR Stok','KPR Officer','Lapangan','Kasir 1','Kasir 2'
        ]) && $user->teams()->where('id', $gcv_kpr->team_id)->exists();
    }

    public function create(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','KPR Officer']);
    }

    public function update(User $user, gcv_kpr $gcv_kpr): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','KPR Officer'])
            && $user->teams()->where('id', $gcv_kpr->team_id)->exists();
    }

    public function delete(User $user, gcv_kpr $gcv_kpr): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','KPR Officer'])
            && $user->teams()->where('id', $gcv_kpr->team_id)->exists();
    }

    public function restore(User $user, gcv_kpr $gcv_kpr): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin'])
            && $user->teams()->where('id', $gcv_kpr->team_id)->exists();
    }

    public function forceDelete(User $user, gcv_kpr $gcv_kpr): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin'])
            && $user->teams()->where('id', $gcv_kpr->team_id)->exists();
    }
}