<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>@yield('title', 'Мототехника') — {{ config('app.name', 'МотоШоп') }}</title>
    <script>
        (function () {
            try {
                var k = 'moto-shop-theme';
                var t = localStorage.getItem(k);
                if (t !== 'light' && t !== 'dark') {
                    t = 'light';
                }
                document.documentElement.setAttribute('data-theme', t);
            } catch (e) {}
        })();
    </script>
    <link rel="stylesheet" href="{{ asset('css/shop.css') }}">
    @stack('head')
</head>
<body>
    <div class="top-bar">
        <div class="container top-bar__inner">
            <div>
                <span>Чебоксары</span>
                <span aria-hidden="true"> · </span>
                <a href="tel:+79000000000">+7 (900) 000-00-00</a>
                <span aria-hidden="true"> · </span>
                <span>Пн–Вс 9:00–18:00</span>
            </div>
            <label class="theme-switch">
                <span class="sr-only">Тёмная тема</span>
                <span aria-hidden="true">Тема</span>
                <input type="checkbox" id="theme-toggle" role="switch" aria-checked="false">
                <span class="theme-switch__track" aria-hidden="true">
                    <span class="theme-switch__thumb"></span>
                </span>
            </label>
        </div>
    </div>

    <header class="site-header">
        <div class="container site-header__main">
            <a href="{{ route('home') }}" class="logo">
                <span class="logo__mark">{{ config('app.name', 'МОТОШОП') }}</span>
                <span class="logo__sub">мототехника и экипировка</span>
            </a>
            <div class="search-wrap">
                <form class="search" action="{{ route('search') }}" method="get" role="search" id="header-search-form">
                    <label class="sr-only" for="q">Поиск по каталогу</label>
                    <input id="q" name="q" type="search" value="{{ request('q') }}" placeholder="Поиск: мопед, шлем, масло…" autocomplete="off" data-search-suggest="{{ route('search.suggest') }}">
                    <button type="submit">Найти</button>
                </form>
                <div id="search-suggest" class="search-suggest" hidden></div>
            </div>
            <nav class="header-actions" aria-label="Быстрые действия">
                <a href="{{ route('catalog.category', 'mototekhnika') }}">Каталог</a>
                @guest
                    <a href="{{ route('login') }}">Вход</a>
                    <a href="{{ route('register') }}">Регистрация</a>
                @else
                    @if (auth()->user()?->is_admin)
                        <a href="{{ route('admin.products.index') }}">Админ</a>
                    @endif
                    <span class="header-user">{{ auth()->user()->email }}</span>
                    <form method="post" action="{{ route('logout') }}" class="header-logout-form">
                        @csrf
                        <button type="submit" class="link-button">Выйти</button>
                    </form>
                @endguest
                <div class="cart-wrap">
                    <a href="{{ route('cart.index') }}" class="cart-toggle">
                        Корзина
                        @if ($cartState['total_qty'] > 0)
                            <span class="cart-badge">{{ $cartState['total_qty'] }}</span>
                        @endif
                    </a>
                    <div class="cart-preview" aria-label="Содержимое корзины">
                        @if ($cartState['lines']->isEmpty())
                            <p class="cart-preview__empty">Корзина пуста</p>
                        @else
                            <ul class="cart-preview__list">
                                @foreach ($cartState['lines']->take(5) as $line)
                                    <li>
                                        <span class="cart-preview__title">{{ $line['product']->title }}</span>
                                        <span class="cart-preview__meta">{{ $line['qty'] }} × {{ number_format($line['product']->price, 0, ',', ' ') }} ₽</span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="cart-preview__footer">
                                <span>Итого: <strong>{{ number_format($cartState['total_sum'], 0, ',', ' ') }} ₽</strong></span>
                                <a class="btn btn--primary btn--sm" href="{{ route('cart.index') }}">В корзину</a>
                            </div>
                        @endif
                    </div>
                </div>
            </nav>
        </div>
        <div class="nav-strip">
            <nav class="container nav-strip__inner" aria-label="Разделы каталога">
                <a href="{{ route('catalog.category', 'mototekhnika') }}">Мототехника</a>
                <a href="{{ route('catalog.category', 'ekipirovka') }}">Экипировка</a>
                <a href="{{ route('catalog.category', 'zapchasti') }}">Запчасти</a>
            </nav>
        </div>
    </header>

    <main class="container">
        @if (session('status'))
            <div class="flash flash--ok" role="status">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="flash flash--err" role="alert">
                <ul>
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <h3>Покупателям</h3>
                    <ul>
                        <li><a href="{{ route('pages.delivery') }}">Доставка и оплата</a></li>
                        <li><a href="{{ route('pages.installment') }}">Рассрочка</a></li>
                        <li><a href="{{ route('pages.warranty') }}">Гарантия</a></li>
                    </ul>
                </div>
                <div>
                    <h3>Компания</h3>
                    <ul>
                        <li><a href="{{ route('pages.about') }}">О магазине</a></li>
                        <li><a href="{{ route('pages.contacts') }}">Контакты</a></li>
                    </ul>
                </div>
                <div>
                    <h3>Каталог</h3>
                    <ul>
                        <li><a href="{{ route('catalog.category', 'mototekhnika') }}">Мототехника</a></li>
                        <li><a href="{{ route('catalog.category', 'ekipirovka') }}">Экипировка</a></li>
                        <li><a href="{{ route('catalog.category', 'zapchasti') }}">Запчасти</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© 2026 МотоШоп</span>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/theme.js') }}" defer></script>
    <script src="{{ asset('js/search-suggest.js') }}" defer></script>
    <script src="{{ asset('js/cart-preview.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
