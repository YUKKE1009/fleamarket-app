<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoldItem extends Model
{
    protected $fillable = ['item_id', 'user_id'];

    // 商品へのリレーション
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
