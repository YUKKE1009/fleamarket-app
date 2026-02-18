<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExhibitionController extends Controller
{
    /**
     * 商品出品画面を表示 (PG08)
     */
    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('items.create', compact('categories', 'conditions'));
    }

    /**
     * 商品出品処理を実行 (P-08)
     */
    public function store(ExhibitionRequest $request)
    {
        $user = Auth::user();

        // 画像の保存処理（storage/app/public/item_images に保存する場合）
        $imagePath = $request->file('image_url')->store('item_images', 'public');

        // 1. 商品情報の保存
        $item = Item::create([
            'seller_id'    => $user->id,
            'condition_id' => $request->condition_id,
            'name'         => $request->name,
            'description'  => $request->description,
            'price'        => $request->price,
            'image_url'    => $imagePath,
            'brand'        => $request->brand,
        ]);

        // 2. カテゴリーの紐付け (中間テーブルへの保存)
        $item->categories()->attach($request->category_ids);

        return redirect()->route('item.index')->with('message', '商品を出品しました');
    }
}
