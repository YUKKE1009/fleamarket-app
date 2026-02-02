<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item; // Itemモデルを使う宣言

class ItemController extends Controller
{
    // トップページ（商品一覧）を表示するメソッド
    public function index()
    {
        // データベースから全ての商品を取得
        $items = Item::all();

        // resources/views/items/index.blade.php を表示する
        // その際、取得した $items を 'items' という名前で渡す
        return view('items.index', compact('items'));
    }
}
