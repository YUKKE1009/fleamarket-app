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
        // 1. 検索キーワードと現在のタブを取得
        $keyword = $request->input('keyword');
        $tab = $request->query('tab', 'recommend');

        // 2. クエリの基本形を作成
        $query = Item::query();

        // ログインしている場合、自分が出品した商品を除外する (FN014-4)
        if (Auth::check()) {
            $query->where('seller_id', '!=', Auth::id());
        }

        // 3. 商品名で部分一致検索を実行 (FN016-2)
        if (!empty($keyword)) {
            $query->where('name', 'LIKE', "%{$keyword}%");
        }

        // 4. タブによる絞り込み (FN016-3のベース)
        if ($tab === 'mylist') {
            if (Auth::check()) {
                // お気に入り（favorites）リレーションを持つ商品に絞り込む
                $query->whereHas('favorites', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            } else {
                // 未ログインでマイリストなら表示なし
                $query->whereRaw('1 = 0');
            }
        }

        $items = $query->get();

        // 5. キーワードとタブをViewに渡す（状態保持のため）
        return view('items.index', compact('items', 'tab', 'keyword'));
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
