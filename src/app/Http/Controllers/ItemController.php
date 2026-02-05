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
     * クエリパラメータ 'tab' に応じて表示内容を切り替え
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');
        $items = collect();

        if ($tab === 'mylist') {
            // マイリスト表示：ログイン中のみお気に入り商品を取得
            if (Auth::check()) {
                $items = Auth::user()->favoriteItems;
            }
        } else {
            // おすすめ表示：全商品を取得
            $items = Item::all();
        }

        return view('items.index', compact('items', 'tab'));
    }

    /* ==========================================
       2. 商品詳細表示 (PG05)
       ========================================== */

    /**
     * 商品詳細画面を表示
     * リレーション先を一括取得して表示（Eager Loading）
     */
    public function show($id)
    {
        $item = Item::with(['category', 'condition', 'comments.user.profile'])
            ->findOrFail($id);

        return view('items.show', compact('item'));
    }

    /* ==========================================
       3. コメント投稿処理 (P-04)
       ========================================== */

    /**
     * 商品へのコメントを保存
     * バリデーションは CommentRequest で実施
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
