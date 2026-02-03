<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // --- ここからリレーションの定義を追加 ---

    // カテゴリーとの紐付け
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 商品の状態との紐付け
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    // コメントとの紐付け
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // --- ここまで ---

    // (もし $fillable などがあればそのままでOK)
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
}
