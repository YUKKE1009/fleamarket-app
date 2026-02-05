<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

class ItemController extends Controller
{
    /**
     * 商品一覧画面 (PG01)
     * 全ての商品データを取得して表示
     */
    public function index()
    {
        $items = Item::all();
        return view('items.index', compact('items'));
    }

    /**
     * 商品詳細画面 (PG05)
     * 商品、カテゴリ、状態、および関連するコメント(ユーザー・プロフィール込)を取得
     */
    public function show($id)
    {
        // 関連データを一括取得（Eager Loading）してクエリ回数を削減
        $item = Item::with(['category', 'condition', 'comments.user.profile'])
            ->findOrFail($id);

        return view('items.show', compact('item'));
    }

    /**
     * コメント送信処理 (P-04)
     * ログインユーザーによるコメントをDBに保存
     * バリデーションは CommentRequest で実施
     */
    public function comment(CommentRequest $request, $item_id)
    {
        // データの保存
        Comment::create([
            'user_id' => Auth::id(), // auth()->id() でもOK
            'item_id' => $item_id,
            'comment' => $request->comment,
        ]);

        // 送信元画面へ戻り、成功メッセージを表示
        return redirect()->back()->with('success', 'コメントを投稿しました');
    }
}
