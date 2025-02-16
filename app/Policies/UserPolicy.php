<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function update(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }
}
