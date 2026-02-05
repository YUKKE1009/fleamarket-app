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

            {{-- ロゴのすぐ下、検索バーの開始位置から --}}

            @if(!Request::is('login') && !Request::is('register'))
            {{-- ログイン画面でも会員登録画面でもない時だけ、以下を表示する --}}

            {{-- 検索バー --}}
            <div class="header__search">
                <form action="/search" method="GET">
                    <input type="text" name="keyword" placeholder="なにをお探しですか？" class="search-input">
                </form>
            </div>

            {{-- ナビゲーション --}}
            <nav class="header__nav">
                <ul class="nav-list">
                    @auth
                    <li class="nav-item">
                        <form action="/logout" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="logout-button">ログアウト</button>
                        </form>
                    </li>
                    @else
                    <li class="nav-item">
                        <a href="/login" class="nav-link">ログイン</a>
                    </li>
                    @endauth

                    <li class="nav-item">
                        <a href="/mypage" class="nav-link">マイページ</a>
                    </li>
                    <li class="nav-item">
                        <a href="/items/create" class="nav-link btn-sell">出品</a>
                    </li>
                </ul>
            </nav>

            @endif {{-- ここで条件分岐終了 --}}
        </div>
    </header>

    <main class="main-container">
        @yield('content')
    </main>
</body>

</html>