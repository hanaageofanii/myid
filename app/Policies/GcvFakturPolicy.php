<?php

namespace App\Policies;

use App\Models\User;
use App\Models\gcv_faktur;

class GcvFakturPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin')
            || $user->hasRole(['admin','Direksi','Legal Pajak','Legal officer']);
    }

    public function view(User $user, gcv_faktur $gcv_faktur): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Direksi','Legal Pajak','Legal officer'])
                && $user->teams()->where('id', $gcv_faktur->team_id)->exists());
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin')
            || $user->hasRole(['admin','Legal Pajak']);
    }

    public function update(User $user, gcv_faktur $gcv_faktur): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal Pajak'])
                && $user->teams()->where('id', $gcv_faktur->team_id)->exists());
    }

    public function delete(User $user, gcv_faktur $gcv_faktur): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal Pajak'])
                && $user->teams()->where('id', $gcv_faktur->team_id)->exists());
    }

    public function restore(User $user, gcv_faktur $gcv_faktur): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal Pajak'])
                && $user->teams()->where('id', $gcv_faktur->team_id)->exists());
    }

    public function forceDelete(User $user, gcv_faktur $gcv_faktur): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal Pajak'])
                && $user->teams()->where('id', $gcv_faktur->team_id)->exists());
    }
}