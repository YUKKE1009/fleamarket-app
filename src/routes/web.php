<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;


//商品一覧画面（トップ画面）（PG01）
Route::get('/', [ItemController::class, 'index']);

// 商品詳細画面 (PG05)
Route::get('/item/{item_id}', [ItemController::class, 'show']);

// コメント投稿（将来的な実装用）
Route::post('/item/comment', [ItemController::class, 'storeComment'])->middleware('auth');

// コメント送信（P-04）　※ログインしている人だけがコメントできる設定（authミドルウェア）
Route::post('/item/{item_id}/comment', [ItemController::class, 'comment'])
    ->middleware('auth');

// --- 認証関連 (Fortifyを使う場合、基本のルートは自動生成されますが、
// カスタムが必要な場合はここに追記します)

// ログイン後の404エラー対策：常にトップページへリダイレクト
Route::redirect('/home', '/');