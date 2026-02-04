<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

class ItemController extends Controller
{
    // 商品一覧 (PG01)
    public function index()
    {
        $items = Item::all();
        return view('items.index', compact('items'));
    }

    // 商品詳細 (PG05)
    public function show($id) 
    {
        $item = Item::with(['category', 'condition', 'comments.user.profile'])
            ->findOrFail($id);

        return view('items.show', compact('item'));
    }

    // コメント送信処理 (P-04)
    public function comment(CommentRequest $request, $item_id)
    {
        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $item_id,
            'comment' => $request->comment,
        ]);
        return redirect()->back()->with('success', 'コメントを投稿しました');
    }
}
