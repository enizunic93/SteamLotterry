<?php

namespace App\Repositories;

use App\Models\Game;
use App\Models\Lot;
use App\User;

class GameRepository
{
    /**
     * Get all of the tasks for a given user.
     * @param  User  $user
     * @return mixed
     */
    public function forUser(User $user)
    {
//        $places = Game::with(['places' => function ($query) use ($user) {
//            $query->where('user_id', $user->id);
//        }])->get();
//
//        return $places;
    }

    public function all() {
        return Game::all();
    }
}