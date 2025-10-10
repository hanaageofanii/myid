<?php

namespace App\Policies;

use App\Models\User;
use App\Models\gcv_datatanah;

class gcv_datatanahPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Direksi','Legal officer','Legal Pajak']);
    }

    public function view(User $user, gcv_datatanah $gcv_datatanah): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Direksi','Legal officer','Legal Pajak'])
            && $user->teams()->where('id', $gcv_datatanah->team_id)->exists();
    }

    public function create(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Legal officer']);
    }

    public function update(User $user, gcv_datatanah $gcv_datatanah): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Legal officer'])
            && $user->teams()->where('id', $gcv_datatanah->team_id)->exists();
    }

    public function delete(User $user, gcv_datatanah $gcv_datatanah): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Legal officer'])
            && $user->teams()->where('id', $gcv_datatanah->team_id)->exists();
    }

    public function restore(User $user, gcv_datatanah $gcv_datatanah): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Legal officer'])
            && $user->teams()->where('id', $gcv_datatanah->team_id)->exists();
    }

    public function forceDelete(User $user, gcv_datatanah $gcv_datatanah): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin','Legal officer'])
            && $user->teams()->where('id', $gcv_datatanah->team_id)->exists();
    }
}