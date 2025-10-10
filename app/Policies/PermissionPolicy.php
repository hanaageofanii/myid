<?php

namespace App\Policies;

use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole(['admin']);
    }

    public function view(User $user, Permission $permission): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole(['admin']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole(['admin']);
    }

    public function update(User $user, Permission $permission): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole(['admin']);
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole(['admin']);
    }

    public function restore(User $user, Permission $permission): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole(['admin']);
    }

    public function forceDelete(User $user, Permission $permission): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole(['admin']);
    }
}