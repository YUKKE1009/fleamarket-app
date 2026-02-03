@extends('layouts.app')

@section('content')
<main class="item-list__container">
    <h1 class="visually-hidden">商品一覧</h1> {{-- アクセシビリティ用の隠し見出し --}}

    <nav class="item-list__tabs" aria-label="商品絞り込み">
        <ul class="item-list__tab-list">
            <li class="item-list__tab-item">
                <a href="/" class="item-list__tab-link {{ !request()->get('tab') ? 'active' : '' }}">おすすめ</a>
            </li>
            <li class="item-list__tab-item">
                <a href="/?tab=mylist" class="item-list__tab-link {{ request()->get('tab') == 'mylist' ? 'active' : '' }}">マイリスト</a>
            </li>
        </ul>
    </nav>

    <section class="item-list__grid-section">
        <ul class="item-list__grid">
            @foreach ($items as $item)
            <li class="item-list__card">
                <article>
                    <a href="/item/{{ $item->id }}" class="item-list__link">
                        <figure class="item-list__image-wrapper">
                            <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}">
                            @if(isset($item->is_sold) && $item->is_sold)
                            <figcaption class="item-list__sold-label">SOLD</figcaption>
                            @endif
                        </figure>
                        <h2 class="item-list__item-name">{{ $item->name }}</h2>
                    </a>
                </article>
            </li>
            @endforeach
        </ul>
    </section>
</main>
@endsection