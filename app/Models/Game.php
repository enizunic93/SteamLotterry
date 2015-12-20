<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $dates = ['end_at'];
    protected $fillable = ['lot_id', 'winner_id'];

    public function lot() {
        return $this->belongsTo(Lot::class);
    }

    public function places() {
        return $this->hasMany(Place::class);
    }

    // 1 игра - 1 победитель (игрок)
    // 1 игрок - N игр где он победитель

    public function winner() {
        return $this->belongsTo(User::class);
    }
}