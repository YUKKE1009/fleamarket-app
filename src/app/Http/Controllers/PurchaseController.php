<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\SoldItem;
use App\Models\User;
use App\Models\Profile;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    /* ==========================================
       1. 購入画面の表示 (PG06)
       ========================================== */
    public function show($item_id)
    {

        // 1. まず商品データを取得
        $item = Item::findOrFail($item_id);

        // 2. その後に $item を使って自分の商品かチェック
        if (Auth::check() && (int)$item->seller_id === (int)Auth::id()) {
            return redirect()->route('item.show', ['item_id' => $item->id]);
        }

        // 3. その他の情報を取得
        $user = Auth::user();
        $profile = $user ? $user->profile : null;

        return view('purchase.show', compact('item', 'user', 'profile'));
    }


    /* ==========================================
       2. 購入確定・Stripeへリダイレクト (P-06)
       ========================================== */
    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $profile = $user->profile;

        // 1. 出品者チェック
        if ((int)$item->seller_id === (int)$user->id) {
            return redirect()->route('item.show', ['item_id' => $item->id])
                ->with('error', '自分の商品を購入することはできません。');
        }

        // 2. 先にDBを更新して「SOLD」にする
        $item->update([
            'buyer_id'          => $user->id,
            'payment_method'    => $request->payment_method, // card または konbini
            'shipping_postcode' => $profile->post_code,
            'shipping_address'  => $profile->address,
            'shipping_building' => $profile->building,
        ]);

        // 3. sold_itemsテーブルにも記録
        \App\Models\SoldItem::create([
            'item_id'        => $item->id,
            'user_id'        => $user->id,
            'payment_method' => $request->payment_method,
        ]);

        // 4. Stripeセッション作成
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => [$request->payment_method === 'card' ? 'card' : 'konbini'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('item.index'),
            'cancel_url' => route('purchase.show', ['item_id' => $item->id]),
        ]);

        return redirect($session->url);
    }

    /* ==========================================
       3. 決済成功（または注文確定）後のDB保存処理
       ========================================== */
    public function success(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $profile = $user->profile;

        // セッションから支払い方法を取得（なければデフォルト値を設定）
        $paymentMethod = session('payment_method', 'stripe');

        // すでに購入されていないかチェック
        if (is_null($item->buyer_id)) {
            // 1. itemsテーブルを更新
            $item->update([
                'buyer_id'          => $user->id,
                'payment_method'    => $paymentMethod, // 動的に保存
                'shipping_postcode' => $profile->post_code,
                'shipping_address'  => $profile->address,
                'shipping_building' => $profile->building,
            ]);

            // 2. sold_itemsテーブルに購入履歴を追加
            \App\Models\SoldItem::create([
                'item_id'        => $item->id,
                'user_id'        => $user->id,
                'payment_method' => $paymentMethod,
            ]);

            session()->forget('payment_method');
        }

        return redirect()->route('item.index')->with('message', 'ご購入ありがとうございました！');
    }

    /* ==========================================
       4. 配送先変更関連 (PG07 / P-07)
       ========================================== */

    /**
     * 住所変更ページ表示 (PG07)
     */
    public function edit($item_id) // editAddress から edit に変更
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $profile = $user->profile; // 既存の住所を表示するために取得

        return view('address.edit', compact('item', 'profile'));
    }

    /**
     * 住所更新実行 (P-07)
     */
    public function update(AddressRequest $request, $item_id)
    {
        /** @var \App\Models\User $user */ // ← この一行を追加
        $user = Auth::user();

        // profilesテーブルのデータを更新または作成
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'post_code' => $request->post_code,
                'address'  => $request->address,
                'building' => $request->building,
            ]
        );

        // 完了後、購入画面(PG06)へリダイレクト
        return redirect()->route('purchase.show', ['item_id' => $item_id])
            ->with('message', '配送先住所を更新しました');
    }
}
