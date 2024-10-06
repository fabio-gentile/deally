<?php

namespace App\Policies;

use App\Models\ImageDeal;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ImageDealPolicy
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
    public function view(User $user, ImageDeal $imageDeal): bool
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
    public function update(User $user, ImageDeal $imageDeal): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ImageDeal $imageDeal): Response
    {
        //
        return $user->id === $imageDeal->deal->user_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à supprimer cette image.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ImageDeal $imageDeal): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ImageDeal $imageDeal): bool
    {
        //
    }
}
