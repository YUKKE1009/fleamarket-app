<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Http\Requests\ProfileRequest;

class MypageController extends Controller
{
    /* ==========================================
       1. マイページ表示関連 (PG09 / FN025)
       ========================================== */

    /**
     * マイページ表示（出品・購入一覧の切り替え）
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $page = $request->query('page', 'sell');

        if ($page === 'buy') {
            // --- 購入済み商品の取得 ---
            // itemsテーブルの buyer_id が自分のIDであるものを取得
            $items = Item::where('buyer_id', $user->id)->get();
        } else {
            // --- 出品済み商品の取得 ---
            // user_id を seller_id に修正して取得
            $items = Item::where('seller_id', $user->id)->get();
        }

        return view('mypage.index', compact('user', 'items', 'page'));
    }

    /* ==========================================
       2. プロフィール編集関連 (PG10 / P-09)
       ========================================== */

    /**
     * プロフィール編集画面を表示
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('mypage.edit', compact('user', 'profile'));
    }

    /**
     * プロフィール情報の更新実行
     */
    public function update(ProfileRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. ユーザー名の更新 (usersテーブル)
        $user->update([
            'name' => $request->name,
        ]);

        // 2. プロフィールデータの整理 (profilesテーブル)
        $profileData = [
            'post_code' => $request->post_code,
            'address'  => $request->address,
            'building' => $request->building,
        ];

        // 3. 画像の保存処理
        if ($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('profiles', 'public');
            $profileData['image_url'] = $path;
        }

        // 4. profilesテーブルの更新 (user_id で紐付け)
        // ※profilesテーブルは通常 user_id で紐付けるためここは user_id のままにします
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return redirect()->route('mypage.index')->with('message', 'プロフィールを更新しました');
    }
}
