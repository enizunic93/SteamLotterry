<?php

namespace App\Models;

use App\Helpers\Steam\APIBridge;
use App\User;
use Illuminate\Database\Eloquent\Model;

// Это вещь пользователя
// TODO: баррету это не надо.
class SteamItem extends Model
{
    protected $fillable = ['app_id', 'class_id', 'user_id', 'bot_steam_id'];
    protected $item;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getItem()
    {
        if (!$this->item) {
            $steam = new APIBridge(\Config::get('steam-api.api_key'));
            $this->item = $steam
                ->queryPlayerInventory($this->app_id, $this->bot_steam_id)
                ->findByClassID($this->class_id);
        }

        return $this->item;
    }
}