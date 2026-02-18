@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endpush

@section('content')
<div class="purchase__container">
    <h2 class="address-edit__title">住所の変更</h2>

    <form action="{{ route('address.update', ['item_id' => $item->id]) }}" method="POST" class="address-edit__form">
        @csrf
        @method('PATCH')

        {{-- 郵便番号 --}}
        <div class="address-edit__group">
            <label for="post_code" class="address-edit__label">郵便番号</label>
            {{-- name, id, valueの中身を post_code に変更 --}}
            <input type="text" name="post_code" id="post_code" class="address-edit__input"
                value="{{ old('post_code', $profile->post_code ?? '') }}">

            {{-- errorの対象も post_code に変更 --}}
            @error('post_code')
            <p class="address-edit__error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 住所 --}}
        <div class="address-edit__group">
            <label for="address" class="address-edit__label">住所</label>
            <input type="text" name="address" id="address" class="address-edit__input"
                value="{{ old('address', $profile->address ?? '') }}">
            @error('address')
            <p class="address-edit__error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 建物名 --}}
        <div class="address-edit__group">
            <label for="building" class="address-edit__label">建物名</label>
            <input type="text" name="building" id="building" class="address-edit__input"
                value="{{ old('building', $profile->building ?? '') }}">
            @error('building')
            <p class="address-edit__error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 更新ボタン（既存の purchase__submit-button を流用） --}}
        <button type="submit" class="purchase__submit-button" style="margin-top: 20px;">
            更新する
        </button>
    </form>
</div>
@endsection