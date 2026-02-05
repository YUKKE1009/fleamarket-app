<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\SoldItem;

class Item extends Model
{
    use HasFactory;

    /**
     * 複数代入可能な属性
     *
     * @var array<int, string>
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
       リレーションシップ
       ========================================== */

    /**
     * 出品者 (User) とのリレーション
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * カテゴリー (Category) とのリレーション
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 商品の状態 (Condition) とのリレーション
     */
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    /**
     * コメント一覧 (Comment) とのリレーション
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * お気に入り一覧 (Favorite) とのリレーション
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * 購入情報 (SoldItem) とのリレーション
     */
    public function soldItem()
    {
        return $this->hasOne(SoldItem::class);
    }

    /* ==========================================
       判定用メソッド (ロジック)
       ========================================== */

    /**
     * ログインユーザーがお気に入り登録済みか判定
     *
     * @return bool
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
     *
     * @return bool
     */
    public function isSold()
    {
        // リレーション先のデータが存在するかチェック
        return $this->soldItem()->exists();
    }
}
