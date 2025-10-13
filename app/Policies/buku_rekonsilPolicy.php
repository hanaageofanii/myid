<?php

namespace App\Policies;

use App\Models\User;
use App\Models\buku_rekonsil;

class buku_rekonsilPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Direksi', 'Kasir 1', 'Kasir 2']);
    }

    public function view(User $user, buku_rekonsil $buku_rekonsil): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // cek role + team
        return $user->hasRole(['admin', 'Direksi', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $buku_rekonsil->team_id) // ✅ perbaikan di sini
                ->exists();
    }

    public function create(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
    }

    public function update(User $user, buku_rekonsil $buku_rekonsil): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $buku_rekonsil->team_id) // ✅ perbaikan di sini
                ->exists();
    }

    public function delete(User $user, buku_rekonsil $buku_rekonsil): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $buku_rekonsil->team_id) // ✅ perbaikan di sini
                ->exists();
    }

    public function restore(User $user, buku_rekonsil $buku_rekonsil): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $buku_rekonsil->team_id) // ✅ perbaikan di sini
                ->exists();
    }

    public function forceDelete(User $user, buku_rekonsil $buku_rekonsil): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $buku_rekonsil->team_id) // ✅ perbaikan di sini
                ->exists();
    }
}