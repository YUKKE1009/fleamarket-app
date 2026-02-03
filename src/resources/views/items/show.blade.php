@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<main class="item-detail__container">
    <article class="item-detail__inner">
        {{-- 左側：商品画像 --}}
        <figure class="item-detail__image-box">
            <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}">
        </figure>

        {{-- 右側：詳細情報 --}}
        <section class="item-detail__content">
            <header class="item-detail__header">
                <h1 class="item-detail__name">{{ $item->name }}</h1>
                <p class="item-detail__brand">{{ $item->brand ?? 'ブランド名' }}</p>
                <p class="item-detail__price">
                    <span class="currency">¥</span>{{ number_format($item->price) }}<span class="tax-label">(税込)</span>
                </p>
            </header>

            {{-- いいね・コメント数 --}}
            <div class="item-detail__actions">
                <div class="item-detail__icon-wrapper">
                    <button class="item-detail__icon-btn">
                        <img src="{{ asset('img/icon-heart-defoult.png') }}" alt="いいね" class="item-detail__icon">
                    </button>
                    <span class="item-detail__count">3</span>
                </div>
                <div class="item-detail__icon-wrapper">
                    <div class="item-detail__icon-btn">
                        <img src="{{ asset('img/icon-comment.png') }}" alt="コメント" class="item-detail__icon">
                    </div>
                    <span class="item-detail__count">{{ count($item->comments) }}</span>
                </div>
            </div>

            <button class="item-detail__buy-btn" onclick="location.href='/purchase/{{ $item->id }}'">購入手続きへ</button>

            <section class="item-detail__section">
                <h2 class="item-detail__section-title">商品説明</h2>
                <p class="item-detail__description">{{ $item->description }}</p>
            </section>

            <section class="item-detail__section">
                <h2 class="item-detail__section-title">商品の情報</h2>
                <dl class="item-detail__info-list">
                    <div class="item-detail__info-row">
                        <dt class="item-detail__label">カテゴリー</dt>
                        <dd class="item-detail__tags">
                            <span class="item-detail__category-tag">{{ $item->category->name }}</span>
                        </dd>
                    </div>
                    <div class="item-detail__info-row">
                        <dt class="item-detail__label">商品の状態</dt>
                        <dd class="item-detail__condition-text">{{ $item->condition->name }}</dd>
                    </div>
                </dl>
            </section>

            <section class="item-detail__comment-section">
                <h2 class="item-detail__section-title">コメント({{ count($item->comments) }})</h2>
                <ul class="item-detail__comment-list">
                    @foreach($item->comments as $comment)
                    <li class="item-detail__comment-item">
                        <div class="item-detail__comment-user">
                            <div class="item-detail__user-avatar">
                                @if($comment->user->profile && $comment->user->profile->image_url)
                                <img src="{{ asset($comment->user->profile->image_url) }}" alt="avatar">
                                @endif
                            </div>
                            <span class="item-detail__username">{{ $comment->user->name }}</span>
                        </div>
                        <div class="item-detail__comment-bubble">
                            <p>{{ $comment->comment }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>

                <form action="/item/comment" method="POST" class="item-detail__comment-form">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    <label for="comment" class="item-detail__form-label">商品へのコメント</label>
                    <textarea name="comment" id="comment" class="item-detail__textarea"></textarea>
                    <button type="submit" class="item-detail__comment-btn">コメントを送信する</button>
                </form>
            </section>
        </section>
    </article>
</main>
@endsection