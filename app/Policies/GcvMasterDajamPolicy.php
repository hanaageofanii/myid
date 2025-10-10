<?php

namespace App\Policies;

use App\Models\User;
use App\Models\GcvMasterDajam;

class GcvMasterDajamPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin')
            || $user->hasRole(['admin','Direksi','Legal officer','Legal Pajak']);
    }

    public function view(User $user, GcvMasterDajam $GcvMasterDajam): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Direksi','Legal officer','Legal Pajak'])
                && $user->teams()->where('id', $GcvMasterDajam->team_id)->exists());
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin')
            || $user->hasRole(['admin','Legal officer']);
    }

    public function update(User $user, GcvMasterDajam $GcvMasterDajam): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal officer'])
                && $user->teams()->where('id', $GcvMasterDajam->team_id)->exists());
    }

    public function delete(User $user, GcvMasterDajam $GcvMasterDajam): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal officer'])
                && $user->teams()->where('id', $GcvMasterDajam->team_id)->exists());
    }

    public function restore(User $user, GcvMasterDajam $GcvMasterDajam): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal officer'])
                && $user->teams()->where('id', $GcvMasterDajam->team_id)->exists());
    }

    public function forceDelete(User $user, GcvMasterDajam $GcvMasterDajam): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal officer'])
                && $user->teams()->where('id', $GcvMasterDajam->team_id)->exists());
    }
}
