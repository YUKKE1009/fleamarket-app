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

        {{-- 1. プロフィール画像 (FN027) --}}
        <div class="mypage__img-group">
            <div class="mypage__avatar" id="avatar-container">
                {{-- DBにパスがあれば表示。なければプレースホルダー --}}
                @if(Auth::user()->profile && Auth::user()->profile->image_url)
                <img src="{{ asset('storage/' . Auth::user()->profile->image_url) }}" alt="avatar" id="preview-img">
                @else
                <div class="mypage__avatar-placeholder"></div>
                @endif
            </div>
            <label class="mypage__img-label">
                画像を選択する
                <input type="file" name="image_url" id="img_url_input" style="display:none;" accept="image/png, image/jpeg">
            </label>
        </div>
        @error('img_url') <p class="error-message">{{ $message }}</p> @enderror

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
    // ID指定で確実に要素をキャッチするように変更
    const input = document.getElementById('img_url_input');
    const container = document.getElementById('avatar-container');

    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(event) {
            // コンテナの中身を入れ替える
            container.innerHTML = '';
            const img = document.createElement('img');
            img.src = event.target.result;
            img.id = 'preview-img';
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'cover';
            img.style.borderRadius = '50%';
            container.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush