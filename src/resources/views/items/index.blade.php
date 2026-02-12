@extends('layouts.app')

@section('content')
<main class="item-list__container">
    <h1 class="visually-hidden">商品一覧</h1>

    {{-- ==========================================
       タブメニュー (おすすめ / マイリスト)
       ========================================== --}}
    <nav class="item-list__tabs" aria-label="商品絞り込み">
        <ul class="item-list__tab-list">
            {{-- おすすめタブ：パラメータがない時、または mylist 以外の時にアクティブ --}}
            <li class="item-list__tab-item">
                <a href="/?tab=recommend" class="item-list__tab-link {{ request()->get('tab') !== 'mylist' ? 'active' : '' }}">おすすめ</a>
            </li>
            {{-- マイリストタブ：tab=mylist の時にアクティブ --}}
            <li class="item-list__tab-item">
                <a href="/?tab=mylist" class="item-list__tab-link {{ request()->get('tab') === 'mylist' ? 'active' : '' }}">
                    マイリスト
                </a>
            </li>
        </ul>
    </nav>

    {{-- ==========================================
       商品グリッド表示エリア
       ========================================== --}}
    <section class="item-list__grid-section">
        <ul class="item-list__grid">
            @forelse ($items as $item)
            <li class="item-list__card">
                <a href="/item/{{ $item->id }}" class="item-list__link">
                    {{-- 1. 画像エリア --}}
                    <div class="item-list__image-wrapper">
                        <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}">

                        {{-- 売り切れ判定 (FN015-3) --}}
                        @if(method_exists($item, 'isSold') && $item->isSold())
                        <div class="item-list__sold-label">Sold</div>
                        @endif
                    </div>

                    {{-- 2. 商品名エリア (画像の下に配置) --}}
                    <p class="item-list__item-name">{{ $item->name }}</p>
                </a>
            </li>
            @empty
            {{-- 商品が存在しない場合の表示 (FN015-4) --}}
            <li class="item-list__empty-message" style="list-style: none; text-align: center; width: 100%; margin-top: 50px; color: #888;">
                表示する商品はありません。
            </li>
            @endforelse
        </ul>
    </section>
</main>
@endsection