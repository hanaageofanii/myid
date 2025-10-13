<?php

namespace App\Policies;

use App\Models\User;
use App\Models\gcv_uang_muka;

class GcvUangMukaPolicy
{
    /**
     * Menentukan apakah user dapat melihat daftar data (index).
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Role yang boleh melihat semua data
        return $user->hasRole(['admin', 'Direksi', 'Kasir 1', 'Kasir 2']);
    }

    /**
     * Menentukan apakah user dapat melihat data tertentu.
     */
    public function view(User $user, gcv_uang_muka $gcv_uang_muka): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Hanya boleh melihat data milik tim sendiri
        return $user->hasRole(['admin', 'Direksi', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $gcv_uang_muka->team_id)
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

        // Hanya admin dan kasir yang bisa membuat data
        return $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
    }

    /**
     * Menentukan apakah user dapat memperbarui data.
     */
    public function update(User $user, gcv_uang_muka $gcv_uang_muka): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Hanya admin dan kasir yang bisa update data milik tim sendiri
        return $user->hasRole(['admin', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $gcv_uang_muka->team_id)
                ->exists();
    }

    /**
     * Menentukan apakah user dapat menghapus data.
     */
    public function delete(User $user, gcv_uang_muka $gcv_uang_muka): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $gcv_uang_muka->team_id)
                ->exists();
    }

    /**
     * Menentukan apakah user dapat mengembalikan data yang dihapus.
     */
    public function restore(User $user, gcv_uang_muka $gcv_uang_muka): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $gcv_uang_muka->team_id)
                ->exists();
    }

    /**
     * Menentukan apakah user dapat menghapus permanen data.
     */
    public function forceDelete(User $user, gcv_uang_muka $gcv_uang_muka): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Kasir 1', 'Kasir 2'])
            && $user->teams()
                ->where('teams.id', $gcv_uang_muka->team_id)
                ->exists();
    }
}