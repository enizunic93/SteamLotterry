<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    protected $fillable = ['bot_steam_id', 'item_id', 'price'];
}
