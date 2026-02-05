<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 商品一覧画面（トップ画面） (PG01)
Route::get('/', [ItemController::class, 'index'])->name('item.index');

// 商品詳細画面 (PG05)
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

// コメント送信処理 (P-04)
// ※ログイン済みユーザーのみ許可 (auth)
Route::post('/item/{item_id}/comment', [ItemController::class, 'comment'])
    ->middleware('auth')
    ->name('comment.store');

// --------------------------------------------------------------------------
// 認証・システム関連
// --------------------------------------------------------------------------

// ログイン後の404エラー対策：常にトップページへリダイレクト
Route::redirect('/home', '/');
