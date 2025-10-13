<?php

namespace App\Policies;

use App\Models\User;
use App\Models\gcv_pencairan_akad;

class gcv_pencairan_akadPolicy
{
    /**
     * Menentukan apakah user dapat melihat daftar data (index).
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Direksi', 'Kasir 1', 'Kasir 2']);
    }

    /**
     * Menentukan apakah user dapat melihat data tertentu.
     */
    public function view(User $user, gcv_pencairan_akad $gcv_pencairan_akad): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // ✅ cek role + team agar tidak ambiguous
        return $user->hasRole(['admin', 'Direksi', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $gcv_pencairan_akad->team_id)
                ->exists();
    }

    /**
     * Menentukan apakah user dapat membuat data baru.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
    }

    /**
     * Menentukan apakah user dapat mengedit data.
     */
    public function update(User $user, gcv_pencairan_akad $gcv_pencairan_akad): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $gcv_pencairan_akad->team_id)
                ->exists();
    }

    /**
     * Menentukan apakah user dapat menghapus data.
     */
    public function delete(User $user, gcv_pencairan_akad $gcv_pencairan_akad): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $gcv_pencairan_akad->team_id)
                ->exists();
    }

    /**
     * Menentukan apakah user dapat mengembalikan data yang dihapus.
     */
    public function restore(User $user, gcv_pencairan_akad $gcv_pencairan_akad): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $gcv_pencairan_akad->team_id)
                ->exists();
    }

    /**
     * Menentukan apakah user dapat menghapus permanen data.
     */
    public function forceDelete(User $user, gcv_pencairan_akad $gcv_pencairan_akad): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $gcv_pencairan_akad->team_id)
                ->exists();
    }
}
