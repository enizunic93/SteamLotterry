<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function change(User $user, User $owner) {
        if ($user->compareTo($owner))
            return true;

        if ($user->can('change other profiles')) {
            return true;
        }

        // Это чужой пользователь без права менять профиль другого владельца.
        return false;
    }
}