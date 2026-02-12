<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoldItem extends Model
{
    protected $fillable = ['item_id', 'user_id', 'payment_method'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
