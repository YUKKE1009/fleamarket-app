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

// 商品一覧画面：おすすめ / マイリスト (PG01, PG02)
Route::get('/', [ItemController::class, 'index'])->name('item.index');

// 商品詳細画面 (PG05)
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');


/* ==========================================
   2. 認証必須ルート（要ログイン）
   ========================================== */

Route::middleware('auth')->group(function () {

   // --- 商品アクション ---
   // コメント送信処理 (P-04)
   Route::post('/item/{item_id}/comment', [ItemController::class, 'comment'])->name('comment.store');

   // お気に入り登録・解除 (P-05)
   Route::post('/item/{item_id}/favorite', [FavoriteController::class, 'store'])->name('favorite.store');


   // --- 購入・決済関連 (PG06, P-06) ---
   // 商品購入画面（購入確認ページ）
   Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase.show');

   // 購入確定処理（PG06※Stripe決済実行）
   Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');

   // 決済成功後の処理 (P-06)
   Route::get('/purchase/success/{item_id}', [PurchaseController::class, 'success'])->name('purchase.success');

   // 住所変更ページ (PG07)
   Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'edit'])->name('purchase.address.edit');

   // 住所更新実行 (P-07)
   Route::patch('/purchase/address/{item_id}', [PurchaseController::class, 'update'])->name('address.update');

   // --- マイページ・プロフィール関連 ---
   // プロフィール表示・購入/出品一覧 (PG09, PG11, PG12)
   Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');

   // プロフィール編集画面 (PG10)
   Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.edit');

   // プロフ更新実行 (P-09)
   Route::patch('/mypage/profile', [MypageController::class, 'update'])->name('mypage.update');
});


/* ==========================================
   3. その他・システム設定
   ========================================== */

// ログイン後のデフォルト遷移先(/home)をトップへリダイレクト
Route::redirect('/home', '/');
