<?php

namespace App\Models;

use App\Helpers\Steam\APIBridge;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Lot extends Model
{
    protected $fillable = ['app_id', 'class_id', 'bot_steam_id', 'price_per_place', 'places'];
    protected static $items;

    public function getItem()
    {
        if (!self::$items[$this->class_id]) {
            $steam = new APIBridge(\Config::get('steam-api.api_key'));
            self::$items[$this->class_id] = $steam
                ->queryPlayerInventory($this->app_id, $this->bot_steam_id)
                ->findByClassID($this->class_id);
        }

        return self::$items[$this->class_id];
    }
}