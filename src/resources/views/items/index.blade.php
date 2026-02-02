@extends('layouts.app')

@section('content')
<div class="item-list-container">
    {{-- タブ部分 --}}
    <div class="item-tabs">
        <a href="/" class="tab-item active">おすすめ</a>
        <a href="/?tab=mylist" class="tab-item">マイリスト</a>
    </div>

    {{-- 商品グリッド部分 --}}
    <div class="item-grid">
        @foreach ($items as $item)
        <div class="item-card">
            {{-- 詳細ページへのリンク --}}
            <a href="/item/{{ $item->id }}" class="item-link">
                <div class="item-image">
                    {{-- 画像がない時のために asset('img/no-image.png') などの予備を考えておくと◎ --}}
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}">

                    {{-- SOLDラベル（将来用：コメントアウトのインデントを整理） --}}
                    {{--
                    @if(isset($item->is_sold_out) && $item->is_sold_out)
                        <span class="sold-label">SOLD</span>
                    @endif 
                    --}}
                </div>
                <p class="item-name">{{ $item->name }}</p>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection