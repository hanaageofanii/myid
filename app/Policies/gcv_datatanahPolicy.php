<?php

namespace App\Policies;

use App\Models\User;
use App\Models\gcv_datatanah;

class gcv_datatanahPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Direksi', 'Legal officer', 'Legal Pajak']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, gcv_datatanah $gcv_datatanah): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // ✅ Cek role + team
        return $user->hasRole(['admin', 'Direksi', 'Legal officer', 'Legal Pajak'])
            && $user->teams()
                ->where('teams.id', $gcv_datatanah->team_id)
                ->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Legal officer']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, gcv_datatanah $gcv_datatanah): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Legal officer'])
            && $user->teams()
                ->where('teams.id', $gcv_datatanah->team_id)
                ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, gcv_datatanah $gcv_datatanah): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Legal officer'])
            && $user->teams()
                ->where('teams.id', $gcv_datatanah->team_id)
                ->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, gcv_datatanah $gcv_datatanah): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Legal officer'])
            && $user->teams()
                ->where('teams.id', $gcv_datatanah->team_id)
                ->exists();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, gcv_datatanah $gcv_datatanah): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'Legal officer'])
            && $user->teams()
                ->where('teams.id', $gcv_datatanah->team_id)
                ->exists();
    }
}