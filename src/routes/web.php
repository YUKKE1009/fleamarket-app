<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MypageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/* ==========================================
   1. 公開ルート（未ログインでも閲覧可能）
   ========================================== */

// 商品一覧画面（おすすめ / マイリスト） (PG01, PG02)
Route::get('/', [ItemController::class, 'index'])->name('item.index');

// 商品詳細画面 (PG05)
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');


/* ==========================================
   2. 認証必須ルート（要ログイン）
   ========================================== */

Route::middleware('auth')->group(function () {

   // --- 商品アクション関連 ---
   // コメント送信処理 (P-04)
   Route::post('/item/{item_id}/comment', [ItemController::class, 'comment'])->name('comment.store');

   // お気に入り登録・解除 (P-05)
   Route::post('/item/{item_id}/favorite', [FavoriteController::class, 'store'])->name('favorite.store');

   // --- 購入・決済関連 ---
   // 商品購入画面 (PG06)
   Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase.show');

   // 購入確定（決済）実行 (P-06)
   Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');

   // --- マイページ・プロフィール関連 ---
   // プロフィール画面 (PG09, PG11, PG12)
   Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');

   // プロフィール編集画面 (PG10)
   Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.edit');

   // プロフ更新実行 (P-09)
   Route::patch('/mypage/profile', [MypageController::class, 'update'])->name('mypage.update');
});


/* ==========================================
   3. その他・リダイレクト設定
   ========================================== */

// Fortify等のデフォルトリダイレクト先(/home)をトップページへ修正
Route::redirect('/home', '/');
