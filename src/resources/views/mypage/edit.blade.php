{{-- resources/views/mypage/edit.blade.php --}}
@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endpush

@section('content')
<main class="mypage__container">
    <h1 class="mypage__title">プロフィール設定</h1>

    <form action="{{ route('mypage.update') }}" method="POST" enctype="multipart/form-data" class="mypage__form">
        @csrf
        @method('PATCH')

        {{-- プロフィール画像設定エリア --}}
        <div class="mypage__img-group">
            <div class="mypage__avatar">
                @if(Auth::user()->profile && Auth::user()->profile->img_url)
                <img src="{{ asset('storage/' . Auth::user()->profile->img_url) }}" alt="avatar" id="preview">
                @else
                <div class="mypage__avatar-placeholder"></div>
                @endif
            </div>
            <label class="mypage__img-label">
                画像を選択する
                <input type="file" name="img_url" style="display:none;">
            </label>
        </div>

        {{-- 各入力項目 --}}
        {{-- ユーザー名 --}}
        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}">
            @error('name') <p class="error-message">{{ $message }}</p> @enderror
        </div>

        {{-- 郵便番号 --}}
        <div class="form-group">
            <label for="post_code">郵便番号</label>
            <input type="text" name="post_code" id="post_code" value="{{ old('post_code', Auth::user()->profile->post_code ?? '') }}">
            @error('post_code') <p class="error-message">{{ $message }}</p> @enderror
        </div>

        {{-- 住所 --}}
        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" name="address" id="address" value="{{ old('address', Auth::user()->profile->address ?? '') }}">
            @error('address') <p class="error-message">{{ $message }}</p> @enderror
        </div>

        {{-- 建物名 --}}
        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" name="building" id="building" value="{{ old('building', Auth::user()->profile->building ?? '') }}">
            @error('building') <p class="error-message">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="mypage__update-btn">更新する</button>
    </form>
</main>
@endsection