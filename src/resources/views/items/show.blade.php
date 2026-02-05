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

            {{-- いいね・コメント数表示 --}}
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
                    {{-- FN020-3: コメント数の増加表示を確認済み --}}
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

                {{-- 投稿済みのコメント一覧 --}}
                <ul class="item-detail__comment-list">
                    @foreach($item->comments as $comment)
                    <li class="item-detail__comment-item">
                        <div class="item-detail__comment-user">
                            <div class="item-detail__user-avatar">
                                @if($comment->user->profile && $comment->user->profile->image_url)
                                <img src="{{ asset($comment->user->profile->image_url) }}" alt="avatar" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
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

                {{-- FN020-1: ログインユーザーのみフォームを表示、未ログインならメッセージ --}}
                @auth
                {{-- ログイン済み：投稿フォームを表示 --}}
                <form action="/item/{{ $item->id }}/comment" method="POST" class="item-detail__comment-form" novalidate>
                    @csrf
                    <label for="comment" class="item-detail__form-label">商品へのコメント</label>
                    <textarea name="comment" id="comment" class="item-detail__textarea">{{ old('comment') }}</textarea>

                    @error('comment')
                    <p class="auth__error" style="color: red; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="item-detail__comment-btn">コメントを送信する</button>
                </form>
                @else
                {{-- 未ログイン：ログインを促す案内を表示（ボタンを隠す） --}}
                <div class="item-detail__comment-login-msg" style="margin-top: 20px; padding: 20px; background-color: #f0f0f0; border-radius: 5px; text-align: center;">
                    <p style="margin-bottom: 10px;">コメントを投稿するにはログインが必要です。</p>
                    <a href="/login" class="item-detail__comment-btn" style="display: inline-block; text-decoration: none; background-color: #ff4d4b; padding: 10px 20px; color: white; border-radius: 5px;">ログイン画面へ</a>
                </div>
                @endauth
            </section>
        </section>
    </article>
</main>
@endsection