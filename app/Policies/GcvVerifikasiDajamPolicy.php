<?php

namespace App\Policies;

use App\Models\User;
use App\Models\gcv_verifikasi_dajam;

class GcvVerifikasiDajamPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin')
            || $user->hasRole(['admin','Direksi','Legal officer','Legal Pajak']);
    }

    public function view(User $user, gcv_verifikasi_dajam $gcv_verifikasi_dajam): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Direksi','Legal officer','Legal Pajak'])
                && $user->teams()->where('id', $gcv_verifikasi_dajam->team_id)->exists());
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin')
            || $user->hasRole(['admin','Legal officer']);
    }

    public function update(User $user, gcv_verifikasi_dajam $gcv_verifikasi_dajam): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal officer'])
                && $user->teams()->where('id', $gcv_verifikasi_dajam->team_id)->exists());
    }

    public function delete(User $user, gcv_verifikasi_dajam $gcv_verifikasi_dajam): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal officer'])
                && $user->teams()->where('id', $gcv_verifikasi_dajam->team_id)->exists());
    }

    public function restore(User $user, gcv_verifikasi_dajam $gcv_verifikasi_dajam): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal officer'])
                && $user->teams()->where('id', $gcv_verifikasi_dajam->team_id)->exists());
    }

    public function forceDelete(User $user, gcv_verifikasi_dajam $gcv_verifikasi_dajam): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal officer'])
                && $user->teams()->where('id', $gcv_verifikasi_dajam->team_id)->exists());
    }
}