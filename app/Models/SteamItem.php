<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SteamItem extends Model
{
    protected $fillable = ['app_id', 'class_id', 'user_id'];

    /**
     * Get the user that owns the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}