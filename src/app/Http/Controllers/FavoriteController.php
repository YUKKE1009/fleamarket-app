<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * お気に入りの登録・解除を実行
     *
     * @param  int  $item_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($item_id)
    {
        $user_id = Auth::id();

        // すでに「いいね」しているか確認
        $favorite = Favorite::where('item_id', $item_id)
            ->where('user_id', $user_id)
            ->first();

        if ($favorite) {
            // FN018-3: すでにあれば削除（解除）
            $favorite->delete();
        } else {
            // FN018-1: なければ作成（登録）
            Favorite::create([
                'user_id' => $user_id,
                'item_id' => $item_id,
            ]);
        }

        return redirect()->back();
    }
}
