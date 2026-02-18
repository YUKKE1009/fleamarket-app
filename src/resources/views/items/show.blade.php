@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<main class="item-detail__container">
    <article class="item-detail__inner">
        {{-- 商品画像エリア --}}
        <figure class="item-detail__image-box">
            <img src="{{ str_starts_with($item->image_url, 'http') ? $item->image_url : asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}">
        </figure>

        {{-- 詳細情報・操作エリア --}}
        <section class="item-detail__content">
            <header class="item-detail__header">
                <h1 class="item-detail__name">{{ $item->name }}</h1>
                <p class="item-detail__brand">{{ $item->brand ?? 'ブランド名' }}</p>
                <p class="item-detail__price">
                    <span class="currency">¥</span>{{ number_format($item->price) }}<span class="tax-label">(税込)</span>
                </p>
            </header>

            {{-- アクション（いいね・コメント数） --}}
            <div class="item-detail__actions">
                <div class="item-detail__icon-wrapper">
                    <form action="{{ route('favorite.store', $item->id) }}" method="POST" class="item-detail__favorite-form">
                        @csrf
                        <button type="submit" class="item-detail__icon-btn">
                            @php
                            $heartIcon = $item->is_favorited_by_auth_user() ? 'icon-heart-pink.png' : 'icon-heart-defoult.png';
                            @endphp
                            <img src="{{ asset('img/' . $heartIcon) }}" alt="いいね" class="item-detail__icon">
                        </button>
                    </form>
                    <span class="item-detail__count">{{ count($item->favorites) }}</span>
                </div>

                <div class="item-detail__icon-wrapper">
                    <div class="item-detail__icon-btn">
                        <img src="{{ asset('img/icon-comment.png') }}" alt="コメント数" class="item-detail__icon">
                    </div>
                    <span class="item-detail__count">{{ count($item->comments) }}</span>
                </div>
            </div>

            {{-- 商品が売り切れ(buyer_idがある)かどうかの判定 --}}
            @if($item->isSold())
            <div class="item-detail__sold-wrapper">
                <button type="button" class="item-detail__buy-btn sold-out" disabled>
                    売り切れました
                </button>
                <p class="item-detail__sold-msg">※この商品はすでに購入されています</p>
            </div>
            @else
            {{-- まだ売れていない場合は通常の購入ボタン --}}
            <button type="button" class="item-detail__buy-btn" onclick="location.href='/purchase/{{ $item->id }}'">
                購入手続きへ
            </button>
            @endif

            {{-- 商品説明 --}}
            <section class="item-detail__section">
                <h2 class="item-detail__section-title">商品説明</h2>
                <p class="item-detail__description">{{ $item->description }}</p>
            </section>

            {{-- 商品詳細情報 --}}
            <section class="item-detail__section">
                <h2 class="item-detail__section-title">商品の情報</h2>
                <dl class="item-detail__info-list">
                    <div class="item-detail__info-row">
                        <dt class="item-detail__label">カテゴリー</dt>
                        <dd class="item-detail__tags">
                            @foreach($item->categories as $category)
                            <span class="item-detail__category-tag">{{ $category->content }}</span>
                            @endforeach
                        </dd>
                    </div>
                    <div class="item-detail__info-row">
                        <dt class="item-detail__label">商品の状態</dt>
                        <dd class="item-detail__condition-text">{{ $item->condition->name }}</dd>
                    </div>
                </dl>
            </section>

            {{-- コメントセクション --}}
            <section class="item-detail__comment-section">
                <h2 class="item-detail__section-title">コメント({{ count($item->comments) }})</h2>

                <ul class="item-detail__comment-list">
                    @foreach($item->comments as $comment)
                    <li class="item-detail__comment-item">
                        <div class="item-detail__comment-user">
                            <div class="item-detail__user-avatar">
                                @if($comment->user->profile?->image_url)
                                <img src="{{ asset($comment->user->profile->image_url) }}" alt="ユーザーアイコン">
                                @endif
                            </div>
                            <span class="item-detail__username">{{ $comment->user->name }}</span>
                        </div>
                        <div class="item-detail__comment-content">
                            <p>{{ $comment->comment }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>

                <form action="{{ Auth::check() ? route('comment.store', $item->id) : route('login') }}"
                    method="{{ Auth::check() ? 'POST' : 'GET' }}"
                    class="item-detail__comment-form">
                    @csrf
                    <label for="comment" class="item-detail__form-label">商品へのコメント</label>
                    <textarea name="comment" id="comment" class="item-detail__textarea">{{ old('comment') }}</textarea>

                    @error('comment')
                    <p class="auth__error">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="item-detail__comment-btn">
                        {{ Auth::check() ? 'コメントを送信する' : 'ログインして送信する' }}
                    </button>
                </form>
            </section>
        </section>
    </article>
</main>
@endsection