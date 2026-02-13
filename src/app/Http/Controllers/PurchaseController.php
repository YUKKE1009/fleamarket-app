<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\SoldItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
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

        return redirect()->route('item.show', ['item_id' => $item->id])
            ->with('message', '購入が完了しました！');
    }

    /* ==========================================
       4. 配送先変更関連 (PG07 / P-07)
       ========================================== */
    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('address.edit', compact('item'));
    }

    public function updateAddress(Request $request, $item_id)
    {
        // 配送先更新ロジックをここに記述予定
    }
}
