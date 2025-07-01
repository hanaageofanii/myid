<?php

namespace App\Policies;

use App\Models\User;
use App\Models\gcv_validasi_pph;

class gcv_validasi_pphPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin','Direksi','Legal officer','Legal Pajak']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, gcv_validasi_pph $gcv_validasi_pph): bool
    {
        return $user->hasRole(['admin','Direksi','Legal officer','Legal Pajak']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin','Legal Pajak']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, gcv_validasi_pph $gcv_validasi_pph): bool
    {
        return $user->hasRole(['admin','Legal Pajak']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, gcv_validasi_pph $gcv_validasi_pph): bool
    {
        return $user->hasRole(['admin','Legal Pajak']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, gcv_validasi_pph $gcv_validasi_pph): bool
    {
        return $user->hasRole(['admin','Legal Pajak']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, gcv_validasi_pph $gcv_validasi_pph): bool
    {
        return $user->hasRole(['admin','Legal Pajak']);
    }
}
