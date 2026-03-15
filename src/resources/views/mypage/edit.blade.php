{{-- プロフィール編集画面 (PG10) --}}
@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endpush

@section('content')
<main class="mypage__container">
    <h1 class="mypage__title">プロフィール設定</h1>

    {{-- P-09: enctypeは必須 --}}
    <form action="{{ route('mypage.update') }}" method="POST" enctype="multipart/form-data" class="mypage__form">
        @csrf
        @method('PATCH')

        {{-- 一時保存した画像パスを保持する --}}
        <input type="hidden" name="temp_img_url" value="{{ old('temp_img_url', session('temp_img_url')) }}">

        {{-- 1. プロフィール画像 (FN027) --}}
        <div class="mypage__img-group">


            <div class="mypage__avatar" id="avatar-container">
                @php
                // 優先順位：1.入力エラー時の値 2.セッションの値
                $displayPath = old('temp_img_url', session('temp_img_url'));
                @endphp

                @if($displayPath)
                {{-- 一時保存画像を表示 --}}
                <img src="{{ asset('storage/' . $displayPath) }}?{{ time() }}" alt="" id="preview-img" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                @elseif(Auth::user()->profile && Auth::user()->profile->image_url)
                {{-- ★ここを修正！ DBにある画像を表示 --}}
                <img src="{{ asset('storage/' . Auth::user()->profile->image_url) }}?{{ time() }}" alt="" id="preview-img" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                @else
                <div class="mypage__avatar-placeholder"></div>
                @endif
            </div>

            <label class="mypage__img-label">
                画像を選択する
                <input type="file" name="img_url" id="img_url_input" style="display:none;" accept="image/png, image/jpeg">
            </label>
        </div>

        @error('img_url')
        <p class="error-message">{{ $message }}</p>
        @enderror

        {{-- 2〜5. 各入力項目 (初期値表示：US008) --}}
        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}">
            @error('name') <p class="error-message">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="post_code">郵便番号</label>
            <input type="text" name="post_code" id="post_code" value="{{ old('post_code', Auth::user()->profile->post_code ?? '') }}">
            @error('post_code') <p class="error-message">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" name="address" id="address" value="{{ old('address', Auth::user()->profile->address ?? '') }}">
            @error('address') <p class="error-message">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" name="building" id="building" value="{{ old('building', Auth::user()->profile->building ?? '') }}">
            @error('building') <p class="error-message">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="mypage__update-btn">更新する</button>
    </form>
</main>
@endsection

@push('scripts')
<script>
    const input = document.getElementById('img_url_input');
    const container = document.getElementById('avatar-container'); // ここをcontainerに戻す

    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // ★この数行（value = '' の処理）を丸ごと削除してください！！
        // これがあると、エラーで戻ってきた時に「どの画像か」を忘れてしまいます。

        const reader = new FileReader();
        reader.onload = function(event) {
            container.innerHTML = '';
            const img = document.createElement('img');
            img.src = event.target.result;
            img.id = 'preview-img';
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'cover';
            img.style.borderRadius = '50%';
            container.appendChild(img);

            console.log("Preview updated"); // 念のためログを出す

        };
        reader.readAsDataURL(file);
    });
</script>
@endpush