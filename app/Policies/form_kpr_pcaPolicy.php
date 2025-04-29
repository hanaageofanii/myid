<?php

namespace App\Policies;

use App\Models\User;
use App\Models\form_kpr_pca;
use Illuminate\Auth\Access\Response;

class form_kpr_pcaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, form_kpr_pca $formKprPca): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, form_kpr_pca $formKprPca): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, form_kpr_pca $formKprPca): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, form_kpr_pca $formKprPca): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, form_kpr_pca $formKprPca): bool
    {
        //
    }
}
