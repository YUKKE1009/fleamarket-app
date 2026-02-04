<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // 保存を許可するカラムを指定
    protected $fillable = [
        'user_id',
        'item_id',
        'comment',
    ];

    /**
     * リレーションシップ：コメントはユーザーに属する
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * リレーションシップ：コメントは商品に属する
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
