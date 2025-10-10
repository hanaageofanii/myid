<?php

namespace App\Policies;

use App\Models\User;
use App\Models\gcv_validasi_pph;

class gcv_validasi_pphPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin')
            || $user->hasRole(['admin','Direksi','Legal officer','Legal Pajak']);
    }

    public function view(User $user, gcv_validasi_pph $gcv_validasi_pph): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Direksi','Legal officer','Legal Pajak'])
                && $user->teams()->where('id', $gcv_validasi_pph->team_id)->exists());
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin')
            || $user->hasRole(['admin','Legal Pajak']);
    }

    public function update(User $user, gcv_validasi_pph $gcv_validasi_pph): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal Pajak'])
                && $user->teams()->where('id', $gcv_validasi_pph->team_id)->exists());
    }

    public function delete(User $user, gcv_validasi_pph $gcv_validasi_pph): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal Pajak'])
                && $user->teams()->where('id', $gcv_validasi_pph->team_id)->exists());
    }

    public function restore(User $user, gcv_validasi_pph $gcv_validasi_pph): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal Pajak'])
                && $user->teams()->where('id', $gcv_validasi_pph->team_id)->exists());
    }

    public function forceDelete(User $user, gcv_validasi_pph $gcv_validasi_pph): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal Pajak'])
                && $user->teams()->where('id', $gcv_validasi_pph->team_id)->exists());
    }
}