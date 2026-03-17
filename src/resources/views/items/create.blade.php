@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endpush

@section('content')
<div class="exhibition__container">
    <h2 class="exhibition__title">商品の出品</h2>

    <form action="{{ route('exhibition.store') }}" method="POST" enctype="multipart/form-data" class="exhibition__form" novalidate>
        @csrf
        {{-- 重要：一時保存されたパスを保持 --}}
        <input type="hidden" name="temp_img_url" id="temp_img_url" value="{{ old('temp_img_url') }}">

        {{-- 商品画像セクション --}}
        <div class="exhibition__section">
            <label class="exhibition__label">商品画像</label>
            <div class="exhibition__image-upload">

                {{-- old('temp_img_url') がある時だけ画像を表示 --}}
                <div id="image-preview" class="image-preview" style="{{ old('temp_img_url') ? 'display: block;' : 'display: none;' }}">
                    @if(old('temp_img_url'))
                    {{-- パスの先頭に / をつけて、storageの前に / を忘れないようにします --}}
                    <img id="preview-img" src="{{ url('storage/' . old('temp_img_url')) }}" alt="プレビュー">
                    @else
                    <img id="preview-img" src="" alt="プレビュー">
                    @endif
                </div>

                <div class="image-upload__placeholder">
                    <label for="image_url" class="image-upload__button">画像を選択する</label>
                    <input type="file" name="image_url" id="image_url" accept="image/jpeg,image/png" hidden onchange="uploadExhibitionImage(this)">
                </div>
            </div>
            @error('image_url') <p class="error-message">{{ $message }}</p> @enderror
        </div>

        <h3 class="exhibition__sub-title">商品の詳細</h3>
        <hr>

        {{-- カテゴリー --}}
        <div class="exhibition__group">
            <label class="exhibition__label">カテゴリー</label>
            <div class="category__tags">
                @foreach($categories as $category)
                <div class="category__tag">
                    <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" id="cat-{{ $category->id }}"
                        {{ is_array(old('category_ids')) && in_array($category->id, old('category_ids')) ? 'checked' : '' }}>
                    <label for="cat-{{ $category->id }}">{{ $category->name }}</label>
                </div>
                @endforeach
            </div>
            @error('category_ids') <p class="error-message">{{ $message }}</p> @enderror
        </div>

        {{-- 商品の状態 --}}
        <div class="exhibition__group">
            <label for="condition_id" class="exhibition__label">商品の状態</label>
            <select name="condition_id" id="condition_id" class="exhibition__select">
                <option value="" disabled selected>選択してください</option>
                @foreach($conditions as $condition)
                <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                    {{ $condition->name }}
                </option>
                @endforeach
            </select>
            @error('condition_id') <p class="error-message">{{ $message }}</p> @enderror
        </div>

        <h3 class="exhibition__sub-title">商品名と説明</h3>
        <hr>

        <div class="exhibition__group">
            <label for="name" class="exhibition__label">商品名</label>
            <input type="text" name="name" id="name" class="exhibition__input" value="{{ old('name') }}">
            @error('name') <p class="error-message">{{ $message }}</p> @enderror
        </div>

        <div class="exhibition__group">
            <label for="brand" class="exhibition__label">ブランド名</label>
            <input type="text" name="brand" id="brand" class="exhibition__input" value="{{ old('brand') }}">
        </div>

        <div class="exhibition__group">
            <label for="description" class="exhibition__label">商品の説明</label>
            <textarea name="description" id="description" class="exhibition__textarea" rows="5">{{ old('description') }}</textarea>
            @error('description') <p class="error-message">{{ $message }}</p> @enderror
        </div>

        <div class="exhibition__group">
            <label for="price" class="exhibition__label">販売価格</label>
            <div class="price__input-wrapper">
                <span class="price__currency">¥</span>
                <input type="text" name="price" id="price" class="exhibition__input"
                    value="{{ old('price') }}"
                    oninput="value = value.replace(/[^0-9]+/g, '');">
            </div>
            @error('price') <p class="error-message">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="exhibition__submit-button">出品する</button>
    </form>
</div>

<script>
    async function uploadExhibitionImage(input) {
        // 1. 必要な要素（プレビューエリア、プレビュー画像、一時パス保存用の隠し入力）を取得
        const previewContainer = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        const tempInput = document.getElementById('temp_img_url');

        // ファイルが選択されていない場合は処理を終了
        if (!input.files || !input.files[0]) return;

        const file = input.files[0];
        const formData = new FormData();
        formData.append('image', file); // 'image' というキーでファイルをセット
        formData.append('_token', '{{ csrf_token() }}'); // CSRFトークンを付与

        try {
            // 2. UploadController（一時保存用）へ送信
            const response = await fetch('{{ route("exhibition.upload.temp") }}', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) throw new Error('アップロードに失敗しました');

            const data = await response.json();

            // 3. サーバーから返ってきた一時パス（tmp/xxxx.jpg など）を隠し入力にセット
            if (tempInput) {
                tempInput.value = data.path;
            }

            // 4. ブラウザ上でプレビュー画像を表示
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                // プレビューエリアを表示（CSSに合わせて display を調整してください）
                previewContainer.style.display = 'flex';
            }
            reader.readAsDataURL(file);

        } catch (error) {
            console.error(error);
            alert('画像のアップロードに失敗しました。もう一度試してください。');
        }
    }
</script>
@endsection