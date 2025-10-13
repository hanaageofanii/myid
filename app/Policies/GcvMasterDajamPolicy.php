<?php

namespace App\Policies;

use App\Models\User;
use App\Models\GcvMasterDajam;

class GcvMasterDajamPolicy
{
    /**
     * Menentukan apakah user dapat melihat daftar data.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Direksi', 'Legal officer', 'Legal Pajak']);
    }

    /**
     * Menentukan apakah user dapat melihat data tertentu.
     */
    public function view(User $user, GcvMasterDajam $gcvMasterDajam): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Direksi', 'Legal officer', 'Legal Pajak'])
            && $user->teams()
                ->where('teams.id', $gcvMasterDajam->team_id)
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

        return $user->hasRole(['admin', 'Legal officer']);
    }

    /**
     * Menentukan apakah user dapat memperbarui data.
     */
    public function update(User $user, GcvMasterDajam $gcvMasterDajam): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Legal officer'])
            && $user->teams()
                ->where('teams.id', $gcvMasterDajam->team_id)
                ->exists();
    }

    /**
     * Menentukan apakah user dapat menghapus data.
     */
    public function delete(User $user, GcvMasterDajam $gcvMasterDajam): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Legal officer'])
            && $user->teams()
                ->where('teams.id', $gcvMasterDajam->team_id)
                ->exists();
    }

    /**
     * Menentukan apakah user dapat mengembalikan data yang dihapus.
     */
    public function restore(User $user, GcvMasterDajam $gcvMasterDajam): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Legal officer'])
            && $user->teams()
                ->where('teams.id', $gcvMasterDajam->team_id)
                ->exists();
    }

    /**
     * Menentukan apakah user dapat menghapus permanen data.
     */
    public function forceDelete(User $user, GcvMasterDajam $gcvMasterDajam): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Legal officer'])
            && $user->teams()
                ->where('teams.id', $gcvMasterDajam->team_id)
                ->exists();
    }
}
