<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

        // 名前をプロフィール専用の temp_profile_img に変更
        $temp_img_url = old('temp_profile_img', '');

        return view('mypage.edit', compact('user', 'profile', 'temp_img_url'));
    }

    /**
     * プロフィール情報の更新実行
     */
    public function update(ProfileRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update(['name' => $request->name]);

        $profileData = [
            'post_code' => $request->post_code,
            'address'   => $request->address,
            'building'  => $request->building,
        ];

        if ($request->hasFile('img_url')) {
            // 新しくアップロードされた場合
            $path = $request->file('img_url')->store('profiles', 'public');
            $profileData['image_url'] = $path;
        } elseif ($request->temp_profile_img) {
            // ★重要：一時保存(tmp)にある画像を、正式な(profiles)フォルダに移動させる
            $oldPath = $request->temp_profile_img;// tmp/xxx.jpg
            $newPath = str_replace('tmp/', 'profiles/', $oldPath);

            // ファイルを移動（Storageファサードを使うのが理想ですが、簡易的に）
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->move($oldPath, $newPath);
                $profileData['image_url'] = $newPath;
            }
        }

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return redirect()->route('mypage.index')->with('message', 'プロフィールを更新しました');
    }
}
