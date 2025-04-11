<?php

namespace App\Policies;

use App\Models\User;
use App\Models\verifikasi_dajam;

class verifikasi_dajamPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin','Marketing','Super admin','Direksi','Staff','Staff Legal','Staff KPR','Legal Officer']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, verifikasi_dajam $verifikasi_dajam): bool
    {
        return $user->hasRole(['admin','Marketing','Super admin','Direksi','Legal Officer']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, verifikasi_dajam $verifikasi_dajam): bool
    {
        return $user->hasRole(['admin','Super admin','Direksi','Staff','Staff KPR','Staff Legal']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, verifikasi_dajam $verifikasi_dajam): bool
    {
        return $user->hasRole(['admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, verifikasi_dajam $verifikasi_dajam): bool
    {
        return $user->hasRole(['admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, verifikasi_dajam $verifikasi_dajam): bool
    {
        return $user->hasRole(['admin']);
    }
}

