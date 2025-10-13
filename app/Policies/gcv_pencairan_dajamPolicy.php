<?php

namespace App\Policies;

use App\Models\User;
use App\Models\gcv_pencairan_dajam;

class gcv_pencairan_dajamPolicy
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
    public function view(User $user, gcv_pencairan_dajam $gcv_pencairan_dajam): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // ✅ cek role + team agar tidak ambiguous
        return $user->hasRole(['admin', 'Direksi', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $gcv_pencairan_dajam->team_id)
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
     * Menentukan apakah user dapat memperbarui data.
     */
    public function update(User $user, gcv_pencairan_dajam $gcv_pencairan_dajam): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $gcv_pencairan_dajam->team_id)
                ->exists();
    }

    /**
     * Menentukan apakah user dapat menghapus data.
     */
    public function delete(User $user, gcv_pencairan_dajam $gcv_pencairan_dajam): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $gcv_pencairan_dajam->team_id)
                ->exists();
    }

    /**
     * Menentukan apakah user dapat mengembalikan data yang dihapus.
     */
    public function restore(User $user, gcv_pencairan_dajam $gcv_pencairan_dajam): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $gcv_pencairan_dajam->team_id)
                ->exists();
    }

    /**
     * Menentukan apakah user dapat menghapus permanen data.
     */
    public function forceDelete(User $user, gcv_pencairan_dajam $gcv_pencairan_dajam): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $gcv_pencairan_dajam->team_id)
                ->exists();
    }
}
