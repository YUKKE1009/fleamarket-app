@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endpush

@section('content')
<main class="mypage__container">
    {{-- セクション1：ユーザー基本情報 (FN025) --}}
    <section class="mypage__profile-section">
        <div class="mypage__profile-header">
            <figure class="mypage__avatar">
                @if($user->profile && $user->profile->image_url)
                <img src="{{ asset('storage/' . $user->profile->image_url) }}" alt="プロフィール画像">
                @else
                <div class="mypage__avatar-placeholder"></div>
                @endif
            </figure>

            <h1 class="mypage__username">{{ $user->name }}</h1> {{-- 最上位見出し --}}

            <div class="mypage__action">
                <a href="{{ route('mypage.edit') }}" class="mypage__edit-button">プロフィールを編集</a>
            </div>
        </div>
    </section>

    {{-- セクション2：商品一覧タブ (PG11, PG12) --}}
    <nav class="mypage__nav" aria-label="マイページメニュー">
        <ul class="mypage__tab-list">
            <li class="mypage__tab-item">
                <a href="{{ route('mypage.index', ['page' => 'sell']) }}"
                    class="mypage__tab-link {{ $page === 'sell' ? 'active' : '' }}">出品した商品</a>
            </li>
            <li class="mypage__tab-item">
                <a href="{{ route('mypage.index', ['page' => 'buy']) }}"
                    class="mypage__tab-link {{ $page === 'buy' ? 'active' : '' }}">購入した商品</a>
            </li>
        </ul>
    </nav>

    {{-- セクション3：グリッド表示エリア --}}
    <section class="mypage__content">
        <h2 class="visually-hidden">商品一覧</h2> {{-- 階層構造を守るための隠し見出し --}}

        <ul class="item-list__grid">
            @forelse ($items as $item)
            <li class="item-list__card">
                <a href="{{ route('item.show', ['item_id' => $item->id]) }}" class="item-list__link">
                    <figure class="item-list__image-wrapper">
                        <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}">

                        {{-- 売り切れ判定 (商品一覧と同じコード) --}}
                        @if(method_exists($item, 'isSold') && $item->isSold())
                        <div class="item-list__sold-label">Sold</div>
                        @endif
                    </figure>
                    <p class="item-list__name">{{ $item->name }}</p>
                </a>
            </li>
            @empty
            <li class="mypage__empty-message">表示する商品はありません。</li>
            @endforelse
        </ul>
    </section>
</main>
@endsection