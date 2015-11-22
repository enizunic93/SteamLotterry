<?php

namespace App\Repositories;

use App\Models\Lot;
use App\User;

class LotRepository
{
    /**
     * Get all of the tasks for a given user.
     *
     * @param  User  $user
     * @return mixed
     */
    public function forUser(User $user)
    {
        return Lot::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function all() {
        return Lot::all();
    }
}