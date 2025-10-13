<?php

namespace App\Policies;

use App\Models\User;
use App\Models\gcv_verifikasi_dajam;

class GcvVerifikasiDajamPolicy
{
    /**
     * Menentukan apakah user dapat melihat daftar data.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Role yang dapat melihat semua data
        return $user->hasRole(['admin', 'Direksi', 'Legal officer', 'Legal Pajak']);
    }

    /**
     * Menentukan apakah user dapat melihat data tertentu.
     */
    public function view(User $user, gcv_verifikasi_dajam $gcv_verifikasi_dajam): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Hanya bisa melihat data milik tim sendiri
        return $user->hasRole(['admin', 'Direksi', 'Legal officer', 'Legal Pajak'])
            && $user->teams()
                ->where('teams.id', $gcv_verifikasi_dajam->team_id)
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

        // Hanya admin dan legal officer yang dapat membuat data
        return $user->hasRole(['admin', 'Legal officer']);
    }

    /**
     * Menentukan apakah user dapat memperbarui data.
     */
    public function update(User $user, gcv_verifikasi_dajam $gcv_verifikasi_dajam): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Hanya admin dan legal officer yang bisa update data milik tim sendiri
        return $user->hasRole(['admin', 'Legal officer'])
            && $user->teams()
                ->where('teams.id', $gcv_verifikasi_dajam->team_id)
                ->exists();
    }

    /**
     * Menentukan apakah user dapat menghapus data.
     */
    public function delete(User $user, gcv_verifikasi_dajam $gcv_verifikasi_dajam): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Legal officer'])
            && $user->teams()
                ->where('teams.id', $gcv_verifikasi_dajam->team_id)
                ->exists();
    }

    /**
     * Menentukan apakah user dapat mengembalikan data yang dihapus.
     */
    public function restore(User $user, gcv_verifikasi_dajam $gcv_verifikasi_dajam): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Legal officer'])
            && $user->teams()
                ->where('teams.id', $gcv_verifikasi_dajam->team_id)
                ->exists();
    }

    /**
     * Menentukan apakah user dapat menghapus permanen data.
     */
    public function forceDelete(User $user, gcv_verifikasi_dajam $gcv_verifikasi_dajam): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Legal officer'])
            && $user->teams()
                ->where('teams.id', $gcv_verifikasi_dajam->team_id)
                ->exists();
    }
}
