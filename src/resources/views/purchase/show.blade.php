@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endpush

@section('content')
<main class="purchase__container">
    <div class="purchase__wrapper">
        {{-- 左側：詳細エリア --}}
        <div class="purchase__main">
            {{-- 商品情報 (FN021) --}}
            <section class="purchase__item-info">
                <figure class="purchase__item-image">
                    <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}">
                </figure>
                <div class="purchase__item-detail">
                    <h1 class="purchase__item-name">{{ $item->name }}</h1>
                    <p class="purchase__item-price">¥ {{ number_format($item->price) }}</p>
                </div>
            </section>

            <hr class="purchase__divider">

            {{-- 支払い方法選択 (FN023) --}}
            <section class="purchase__payment-section">
                <h2 class="purchase__section-title">支払い方法</h2>
                <div class="purchase__select-wrapper">
                    <select name="payment_method" id="payment_select" form="purchase-form">
                        <option value="" disabled selected>選択してください</option>
                        <option value="konbini">コンビニ支払い</option>
                        <option value="card">カード支払い</option>
                    </select>
                </div>
            </section>

            <hr class="purchase__divider">

            {{-- 配送先情報 (FN021, FN024) --}}
            <section class="purchase__address-section">
                <div class="purchase__address-header">
                    <h2 class="purchase__section-title">配送先</h2>
                    <a href="{{ route('purchase.address.edit', ['item_id' => $item->id]) }}" class="purchase__address-edit">変更する</a>
                </div>
                <div class="purchase__address-content">
                    <p class="purchase__post-code">〒 {{ $profile->post_code ?? '未登録' }}</p>
                    <p class="purchase__address-text">
                        {{ $profile->address ?? '住所を登録してください' }}
                        {{ $profile->building ?? '' }}
                    </p>
                </div>
            </section>
        </div>

        {{-- 右側：サイドバー（小計画面） --}}
        <aside class="purchase__sidebar">
            <div class="purchase__summary-card">
                <table class="purchase__summary-table">
                    <tr>
                        <th>商品代金</th>
                        <td>¥ {{ number_format($item->price) }}</td>
                    </tr>
                    <tr>
                        <th>支払い方法</th>
                        {{-- JSで動的に書き換える --}}
                        <td id="display_payment">選択してください</td>
                    </tr>
                </table>
            </div>

            <form id="purchase-form" action="{{ route('purchase.store', ['item_id' => $item->id]) }}" method="POST">
                @csrf
                {{-- 支払い方法を送信するための隠しフィールド --}}
                <input type="hidden" name="payment_method" id="hidden_payment">

                <button type="submit" class="purchase__submit-button">購入する</button>
            </form>
        </aside>
    </div>
</main>

{{-- 支払い方法の選択を小計画面に反映させるJS (FN023) --}}
<script>
    document.getElementById('payment_select').addEventListener('change', function() {
        const selectedText = this.options[this.selectedIndex].text;
        const selectedValue = this.value;

        // 右側の表示を更新
        document.getElementById('display_payment').textContent = selectedText;
        // フォーム送信用の隠しフィールドに値をセット
        document.getElementById('hidden_payment').value = selectedValue;
    });
</script>
@endsection