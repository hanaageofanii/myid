<?php

namespace App\Policies;

use App\Models\User;
use App\Models\gcv_datatandaterima;

class gcv_datatandaterimaPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Direksi','Legal officer','Legal Pajak']);
    }

    public function view(User $user, gcv_datatandaterima $gcv_datatandaterima): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Direksi','Legal officer','Legal Pajak'])
            && $user->teams()->where('id', $gcv_datatandaterima->team_id)->exists();
    }

    public function create(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Legal officer']);
    }

    public function update(User $user, gcv_datatandaterima $gcv_datatandaterima): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Legal officer'])
            && $user->teams()->where('id', $gcv_datatandaterima->team_id)->exists();
    }

    public function delete(User $user, gcv_datatandaterima $gcv_datatandaterima): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Legal officer'])
            && $user->teams()->where('id', $gcv_datatandaterima->team_id)->exists();
    }

    public function restore(User $user, gcv_datatandaterima $gcv_datatandaterima): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Legal officer'])
            && $user->teams()->where('id', $gcv_datatandaterima->team_id)->exists();
    }

    public function forceDelete(User $user, gcv_datatandaterima $gcv_datatandaterima): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Legal officer'])
            && $user->teams()->where('id', $gcv_datatandaterima->team_id)->exists();
    }
}