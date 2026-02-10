<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\SoldItem;
use App\Http\Requests\ProfileRequest;

class MypageController extends Controller
{
    /* ==========================================
       1. プロフィール画面表示 (PG09)
       ========================================== */

    /**
     * マイページ（プロフィール）を表示
     * 出品済み・購入済みの商品一覧を切り替えて取得 (FN025)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $page = $request->query('page', 'sell');

        if ($page === 'buy') {
            // 購入した商品一覧の取得
            $itemIds = SoldItem::where('user_id', $user->id)->pluck('item_id');
            $items = Item::whereIn('id', $itemIds)->get();
        } else {
            // 出品した商品一覧の取得
            $items = Item::where('user_id', $user->id)->get();
        }

        return view('mypage.index', compact('user', 'items', 'page'));
    }

    /* ==========================================
       2. プロフィール編集・設定 (PG10, P-09)
       ========================================== */

    /**
     * プロフィール編集画面を表示 (PG10)
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('mypage.edit', compact('user', 'profile'));
    }

    /**
     * プロフィール情報の更新実行 (P-09)
     * * @param ProfileRequest $request バリデーション済みリクエスト
     */
    // app/Http/Controllers/MypageController.php

    public function update(ProfileRequest $request)
    {
    // ここでログイン中のユーザーを取得するのが必須です！
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. ユーザー名の更新
        $user->update([
            'name' => $request->name,
        ]);

        // 2. 保存用データの作成（DBのカラム名 image_url に合わせる）
        $profileData = [
            'post_code' => $request->post_code,
            'address'   => $request->address,
            'building'  => $request->building,
        ];

        // 3. 画像の保存処理
        // HTMLのname属性を image_url に変えた場合はここも image_url に！
        if ($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('profiles', 'public');
            $profileData['image_url'] = $path;
        }

        // 4. profilesテーブルの更新
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return redirect()->route('mypage.index');
    }
}
