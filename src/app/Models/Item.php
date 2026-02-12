<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'condition_id',
        'name',
        'brand',
        'price',
        'description',
        'image_url'
    ];

    /* ==========================================
       リレーションシップ
       ========================================== */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function soldItem()
    {
        return $this->hasOne(SoldItem::class);
    }

    /* ==========================================
       判定用メソッド (ロジック)
       ========================================== */

    /**
     * ログインユーザーがお気に入り登録済みか判定
     */
    public function is_favorited_by_auth_user()
    {
        if (!Auth::check()) {
            return false;
        }

        return $this->favorites()->where('user_id', Auth::id())->exists();
    }

    /**
     * 商品が売り切れ(Sold)か判定
     */
    public function isSold()
    {
        return $this->soldItem()->exists();
    }
}