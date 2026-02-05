@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="auth">
    <div class="auth__inner">
        <h1 class="auth__title">会員登録</h1>

        <form class="auth__form" action="/register" method="POST" novalidate>
            @csrf
            {{-- ユーザー名 --}}
            <div class="auth__group">
                <label class="auth__label" for="name">ユーザー名</label>
                <input class="auth__input" type="text" name="name" id="name" value="{{ old('name') }}">
                @error('name')
                <p class="auth__error">{{ $message }}</p>
                @enderror
            </div>

            {{-- メールアドレス --}}
            <div class="auth__group">
                <label class="auth__label" for="email">メールアドレス</label>
                <input class="auth__input" type="email" name="email" id="email" value="{{ old('email') }}">
                @error('email')
                <p class="auth__error">{{ $message }}</p>
                @enderror
            </div>

            {{-- パスワード --}}
            <div class="auth__group">
                <label class="auth__label" for="password">パスワード</label>
                <input class="auth__input" type="password" name="password" id="password">
                @error('password')
                <p class="auth__error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 確認用パスワード --}}
            <div class="auth__group">
                <label class="auth__label" for="password_confirmation">確認用パスワード</label>
                <input class="auth__input" type="password" name="password_confirmation" id="password_confirmation">
            </div>

            {{-- アクションボタン --}}
            <div class="auth__actions">
                <button class="auth__btn" type="submit">登録する</button>
                <a class="auth__link" href="/login">ログインはこちら</a>
            </div>
        </form>
    </div>
</div>
@endsection