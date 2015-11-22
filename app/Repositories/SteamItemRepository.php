<?php

namespace App\Repositories;

use App\Models\SteamItem;
use App\User;

class SteamItemRepository
{
    /**
     * Get all of the tasks for a given user.
     *
     * @param  User $user
     * @return mixed
     */
    public function forUser(User $user)
    {
        return SteamItem::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function forItemInfo($appId, $classId)
    {
        return SteamItem::where(['app_id' => $appId, 'class_id' => $classId])->first();
    }
}