<?php

namespace App\Policies;

use App\Models\User;
use App\Models\gcv_pengajuan_bn;

class gcv_pengajuanbnPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin')
            || $user->hasRole(['admin','Direksi','Legal officer','Legal Pajak']);
    }

    public function view(User $user, gcv_pengajuan_bn $gcv_pengajuan_bn): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Direksi','Legal officer','Legal Pajak'])
                && $user->teams()->where('id', $gcv_pengajuan_bn->team_id)->exists());
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin')
            || $user->hasRole(['admin','Legal officer']);
    }

    public function update(User $user, gcv_pengajuan_bn $gcv_pengajuan_bn): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal officer'])
                && $user->teams()->where('id', $gcv_pengajuan_bn->team_id)->exists());
    }

    public function delete(User $user, gcv_pengajuan_bn $gcv_pengajuan_bn): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal officer'])
                && $user->teams()->where('id', $gcv_pengajuan_bn->team_id)->exists());
    }

    public function restore(User $user, gcv_pengajuan_bn $gcv_pengajuan_bn): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal officer'])
                && $user->teams()->where('id', $gcv_pengajuan_bn->team_id)->exists());
    }

    public function forceDelete(User $user, gcv_pengajuan_bn $gcv_pengajuan_bn): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal officer'])
                && $user->teams()->where('id', $gcv_pengajuan_bn->team_id)->exists());
    }
}
