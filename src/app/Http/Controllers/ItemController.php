<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

class ItemController extends Controller
{
    /* ==========================================
       1. 商品一覧・マイリスト表示 (PG01, PG02)
       ========================================== */

    /**
     * 商品一覧画面を表示
     * クエリパラメータ 'tab' で「おすすめ」と「マイリスト」を切り替え
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');

        if ($tab === 'mylist') {
            // --- マイリスト表示の処理 ---
            if (Auth::check()) {
                // お気に入りテーブル経由で取得
                $itemIds = \App\Models\Favorite::where('user_id', Auth::id())->pluck('item_id');
                $items = Item::whereIn('id', $itemIds)->get();
            } else {
                $items = collect();
            }
        } else {
            // --- おすすめ（通常）表示の処理 ---
            // ★ここが抜けていたので、全商品を取得するようにします
            $items = Item::all();
        }

        return view('items.index', compact('items', 'tab'));
    }

    /* ==========================================
       2. 商品詳細表示 (PG05)
       ========================================== */

    /**
     * 商品詳細画面を表示
     * Eager Loading でコメントやプロフィール情報を一括取得 (N+1問題対策)
     */
    public function show($id)
    {
        $item = Item::with('categories', 'condition', 'comments')->findOrFail($id);

        return view('items.show', compact('item'));
    }

    /* ==========================================
       3. コメント投稿処理 (P-04)
       ========================================== */

    /**
     * 商品へのコメントを保存
     */
    public function comment(CommentRequest $request, $item_id)
    {
        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'コメントを投稿しました');
    }
}
