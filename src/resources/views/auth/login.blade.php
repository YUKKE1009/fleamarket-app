@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="auth">
    <div class="auth__inner">
        <h1 class="auth__title">ログイン</h1>

        <form class="auth__form" action="{{ route('login') }}" method="POST" novalidate>
            @csrf
            <div class="auth__group">
                <label class="auth__label" for="email">メールアドレス</label>
                <input class="auth__input" type="email" name="email" id="email" value="{{ old('email') }}">
                @error('email')
                <p class="auth__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth__group">
                <label class="auth__label" for="password">パスワード</label>
                <input class="auth__input" type="password" name="password" id="password">
                @error('password')
                <p class="auth__error">{{ $message }}</p>
                @enderror
            </div>


            <div class="auth__actions">
                <button class="auth__btn" type="submit">ログインする</button>
                <a class="auth__link" href="/register">会員登録はこちら</a>
            </div>
        </form>
    </div>
</div>
@endsection