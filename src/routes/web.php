<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\FavoriteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/* ==========================================
   1. 商品閲覧・一覧関連
   ========================================== */

// 商品一覧画面（トップ画面 / マイリスト） (PG01, PG02)
Route::get('/', [ItemController::class, 'index'])->name('item.index');

// 商品詳細画面 (PG05)
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');


/* ==========================================
   2. ユーザーアクション関連（要ログイン）
   ========================================== */

Route::middleware('auth')->group(function () {

    // コメント送信処理 (P-04)
    Route::post('/item/{item_id}/comment', [ItemController::class, 'comment'])
        ->name('comment.store');

    // お気に入り登録・解除 (P-05)
    Route::post('/item/{item_id}/favorite', [FavoriteController::class, 'store'])
        ->name('favorite.store');
});


/* ==========================================
   3. システム・リダイレクト関連
   ========================================== */

// ログイン後の404エラー対策：常にトップページへリダイレクト
Route::redirect('/home', '/');
