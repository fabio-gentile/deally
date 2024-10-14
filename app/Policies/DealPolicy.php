<?php

namespace App\Policies;

use App\Models\Deal;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DealPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Deal $deal): Response
    {
        return $user->id === $deal->user_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à modifier ce deal.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Deal $deal): bool
    {
        return $user->id === $deal->user_id;
    }
}
