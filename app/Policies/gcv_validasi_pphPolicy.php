<?php

namespace App\Policies;

use App\Models\User;
use App\Models\gcv_validasi_pph;

class gcv_validasi_pphPolicy
{
    /**
     * Menentukan apakah user dapat melihat daftar data (index).
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
    public function view(User $user, gcv_validasi_pph $gcv_validasi_pph): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // ✅ cek role + team
        return $user->hasRole(['admin', 'Direksi', 'Legal officer', 'Legal Pajak'])
            && $user->teams()
                ->where('teams.id', $gcv_validasi_pph->team_id)
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

        return $user->hasRole(['admin', 'Legal Pajak']);
    }

    /**
     * Menentukan apakah user dapat memperbarui data.
     */
    public function update(User $user, gcv_validasi_pph $gcv_validasi_pph): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Legal Pajak'])
            && $user->teams()
                ->where('teams.id', $gcv_validasi_pph->team_id)
                ->exists();
    }

    /**
     * Menentukan apakah user dapat menghapus data.
     */
    public function delete(User $user, gcv_validasi_pph $gcv_validasi_pph): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Legal Pajak'])
            && $user->teams()
                ->where('teams.id', $gcv_validasi_pph->team_id)
                ->exists();
    }

    /**
     * Menentukan apakah user dapat mengembalikan data yang dihapus.
     */
    public function restore(User $user, gcv_validasi_pph $gcv_validasi_pph): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Legal Pajak'])
            && $user->teams()
                ->where('teams.id', $gcv_validasi_pph->team_id)
                ->exists();
    }

    /**
     * Menentukan apakah user dapat menghapus permanen data.
     */
    public function forceDelete(User $user, gcv_validasi_pph $gcv_validasi_pph): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Legal Pajak'])
            && $user->teams()
                ->where('teams.id', $gcv_validasi_pph->team_id)
                ->exists();
    }
}