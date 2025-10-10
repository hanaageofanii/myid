<?php

namespace App\Policies;

use App\Models\User;
use App\Models\kartu_kontrolGCV;

class KartuKontrolPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin')
            || $user->hasRole(['admin','Direksi','Kasir 1','Kasir 2']);
    }

    public function view(User $user, kartu_kontrolGCV $kartu_kontrolGCV): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Direksi','Kasir 1','Kasir 2'])
                && $user->teams()->where('id', $kartu_kontrolGCV->team_id)->exists());
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin')
            || $user->hasRole(['admin','Kasir 1','Kasir 2']);
    }

    public function update(User $user, kartu_kontrolGCV $kartu_kontrolGCV): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Kasir 1','Kasir 2'])
                && $user->teams()->where('id', $kartu_kontrolGCV->team_id)->exists());
    }

    public function delete(User $user, kartu_kontrolGCV $kartu_kontrolGCV): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Kasir 1','Kasir 2'])
                && $user->teams()->where('id', $kartu_kontrolGCV->team_id)->exists());
    }

    public function restore(User $user, kartu_kontrolGCV $kartu_kontrolGCV): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Kasir 1','Kasir 2'])
                && $user->teams()->where('id', $kartu_kontrolGCV->team_id)->exists());
    }

    public function forceDelete(User $user, kartu_kontrolGCV $kartu_kontrolGCV): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Kasir 1','Kasir 2'])
                && $user->teams()->where('id', $kartu_kontrolGCV->team_id)->exists());
    }
}