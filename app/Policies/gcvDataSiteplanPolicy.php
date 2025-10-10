<?php

namespace App\Policies;

use App\Models\User;
use App\Models\GcvDataSiteplan;

class gcvDataSiteplanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin')
            || $user->hasRole(['admin','Direksi','Legal officer','Legal Pajak']);
    }

    public function view(User $user, GcvDataSiteplan $GcvDataSiteplan): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Direksi','Legal officer','Legal Pajak'])
                && $user->teams()->where('id', $GcvDataSiteplan->team_id)->exists());
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin')
            || $user->hasRole(['admin','Legal officer']);
    }

    public function update(User $user, GcvDataSiteplan $GcvDataSiteplan): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal officer'])
                && $user->teams()->where('id', $GcvDataSiteplan->team_id)->exists());
    }

    public function delete(User $user, GcvDataSiteplan $GcvDataSiteplan): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal officer'])
                && $user->teams()->where('id', $GcvDataSiteplan->team_id)->exists());
    }

    public function restore(User $user, GcvDataSiteplan $GcvDataSiteplan): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal officer'])
                && $user->teams()->where('id', $GcvDataSiteplan->team_id)->exists());
    }

    public function forceDelete(User $user, GcvDataSiteplan $GcvDataSiteplan): bool
    {
        return $user->hasRole('Super Admin')
            || ($user->hasRole(['admin','Legal officer'])
                && $user->teams()->where('id', $GcvDataSiteplan->team_id)->exists());
    }
}