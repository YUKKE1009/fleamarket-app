<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{
    // 購入画面の表示 (PG06)
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $profile = $user->profile; // 配送先として使用

        return view('purchase.show', compact('item', 'user', 'profile'));
    }

    // 購入確定（決済） (P-06)
    public function store(PurchaseRequest $request, $item_id)
    {
        // ここに決済ロジックとDB保存を書きます（Stripe連携など）
        // 現時点では、まず画面が表示されることを目指しましょう！
    }

    // 住所変更画面の表示 (PG07)
    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('address.edit', compact('item')); // resources/views/address/edit.blade.php を探す
    }

    // 住所の更新実行 (P-07)
    public function updateAddress(Request $request, $item_id)
    {
        // ここで一時的な配送先を保存するロジックを書く
    }
    
}