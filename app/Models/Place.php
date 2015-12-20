<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = ['place_id', 'user_id', 'game_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function game() {
        return $this->belongsTo(Game::class);
    }
}
