<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    // 商品一覧 (PG01)
    public function index()
    {
        $items = Item::all();
        return view('items.index', compact('items'));
    }

    // 商品詳細 (PG05)
    public function show($item_id)
    {
        $item = Item::with(['category', 'condition', 'comments.user.profile'])
            ->findOrFail($item_id);

        return view('items.show', compact('item'));
    }
}
