<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class GameHistory extends Model
{
    protected $table = 'game_history';

    // User id = winner
    protected $fillable = ['game_id', 'user_id'];

    public function game() {
        return $this->belongsTo(Game::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}