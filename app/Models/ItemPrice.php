<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPrice extends Model
{
    protected $fillable = ['class_id', 'app_id', 'min', 'median', 'volume'];

    /**
     * TODO: задокументить. Данный метод решает сколько предмет стоит.
     * @return mixed
     */
    public function getSitePrice() {
        return max($this->min, $this->median);
    }
}