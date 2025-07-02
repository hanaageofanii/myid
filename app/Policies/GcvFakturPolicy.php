<?php

namespace App\Policies;

use App\Models\gcv_faktur;
use App\Models\User;

class GcvFakturPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin','Direksi', 'Legal Pajak', 'Legal officer']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user,gcv_faktur $gcv_faktur): bool
    {
        return $user->hasRole(['admin','Direksi', 'Legal Pajak', 'Legal officer']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'Legal Pajak']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user,gcv_faktur $gcv_faktur): bool
    {
        return $user->hasRole(['admin', 'Legal Pajak']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user,gcv_faktur $gcv_faktur): bool
    {
        return $user->hasRole(['admin', 'Legal Pajak']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user,gcv_faktur $gcv_faktur): bool
    {
        return $user->hasRole(['admin', 'Legal Pajak']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user,gcv_faktur $gcv_faktur): bool
    {
        return $user->hasRole(['admin', 'Legal Pajak']);
    }
}
