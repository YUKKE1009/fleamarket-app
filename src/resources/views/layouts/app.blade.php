<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>

    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/item.css') }}">
    @stack('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            {{-- ロゴ --}}
            <div class="header__logo">
                <a href="/">
                    <img src="{{ asset('img/header-logo.png') }}" alt="COACHTECH">
                </a>
            </div>

            {{-- 検索バー --}}
            <div class="header__search">
                <form action="/search" method="GET">
                    <input type="text" name="keyword" placeholder="なにをお探しですか？" class="search-input">
                </form>
            </div>

            {{-- ナビゲーション --}}
            <nav class="header__nav">
                <ul class="nav-list">
                    <li class="nav-item"><a href="/login" class="nav-link">ログイン</a></li>
                    <li class="nav-item"><a href="/mypage" class="nav-link">マイページ</a></li>
                    <li class="nav-item"><a href="/items/create" class="nav-link btn-sell">出品</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-container">
        @yield('content')
    </main>
</body>

</html>