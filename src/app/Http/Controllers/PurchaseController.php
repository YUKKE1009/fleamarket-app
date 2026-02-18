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
        $item = Item::findOrFail($item_id);
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
            'success_url' => route('purchase.success', ['item_id' => $item->id]),
            'cancel_url' => route('purchase.show', ['item_id' => $item->id]),
        ]);

        return redirect($session->url);
    }

    /* ==========================================
       3. 決済成功後のDB保存処理 (FN022)
       ========================================== */
    public function success(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        if (is_null($item->buyer_id)) {
            $item->update([
                'buyer_id'       => Auth::id(),
                'payment_method' => 'stripe',
            ]);
        }

        // 修正：引数を 'item.index' に変更。IDを渡す必要もなくなります。
        return redirect()->route('item.index');
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
    public function update(AddressRequest $request, $item_id) // Request から AddressRequest に変更
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
