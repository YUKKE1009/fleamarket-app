<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'condition_id',
        'name',
        'brand',
        'price',
        'description',
        'image_url',
        'buyer_id',
        'payment_method',
        'shipping_postcode',
        'shipping_address',
        'shipping_building'
    ];

    /* ==========================================
       リレーションシップ
       ========================================== */

    // 出品者
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // 購入者
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
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
        return !is_null($this->buyer_id);
    }
}
