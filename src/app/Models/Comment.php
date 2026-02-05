<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * 複数代入可能な属性
     */
    protected $fillable = [
        'user_id',
        'item_id',
        'comment',
    ];

    /* ==========================================
       リレーションシップの定義
       ========================================== */

    /**
     * コメントを投稿したユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * コメントが投稿された商品
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
