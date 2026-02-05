<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;

    /**
     * 複数代入可能な属性
     */
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
       リレーションシップの定義
       ========================================== */

    /**
     * 商品を所有するユーザー (出品者)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 所属するカテゴリー
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 商品の状態 (新品、目立った傷なし等)
     */
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    /**
     * 商品に紐づくコメント一覧
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * 商品に紐づくお気に入り一覧
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /* ==========================================
       便利な判定用メソッド (Business Logic)
       ========================================== */

    /**
     * 現在ログイン中のユーザーがこの商品を「いいね」しているか判定
     *
     * @return bool
     */
    public function is_favorited_by_auth_user()
    {
        // 未ログイン時は常に false
        if (!Auth::check()) {
            return false;
        }

        return $this->favorites()->where('user_id', Auth::id())->exists();
    }
}
