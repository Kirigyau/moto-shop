<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>@yield('title', 'Админ') — {{ config('app.name') }}</title>
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
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar" id="admin-sidebar" aria-label="Навигация админки">
            <div class="admin-sidebar__brand">
                <a href="{{ route('home') }}" class="admin-sidebar__logo" title="На главную сайта">
                    <span class="admin-sidebar__logo-mark">{{ config('app.name', 'МотоШоп') }}</span>
                    <span class="admin-sidebar__logo-sub">Админ-панель</span>
                </a>
            </div>
            <nav class="admin-sidebar__nav">
                <p class="admin-sidebar__group-title">Каталог</p>

                <a href="{{ route('admin.products.index') }}"
                   class="admin-sidebar__link @if(request()->routeIs('admin.products.index') && !$adminActiveCategory) is-active @endif">
                    <span class="admin-sidebar__icon" aria-hidden="true">📦</span>
                    <span class="admin-sidebar__label">Все товары</span>
                    <span class="admin-sidebar__count">{{ $adminTotalProducts }}</span>
                </a>

                @php
                    $sectionIcons = [
                        'mototekhnika' => '🏍',
                        'ekipirovka' => '🪖',
                        'zapchasti' => '🔧',
                    ];
                @endphp
                @foreach ($adminCategories as $slug => $label)
                    <div class="admin-sidebar__row @if($adminActiveCategory === $slug) is-active-row @endif">
                        <a href="{{ route('admin.products.index', ['category' => $slug]) }}"
                           class="admin-sidebar__link @if($adminActiveCategory === $slug) is-active @endif">
                            <span class="admin-sidebar__icon" aria-hidden="true">{{ $sectionIcons[$slug] ?? '•' }}</span>
                            <span class="admin-sidebar__label">{{ $label }}</span>
                            <span class="admin-sidebar__count">{{ $adminCategoryCounts[$slug] ?? 0 }}</span>
                        </a>
                        <a href="{{ route('admin.products.create', ['category' => $slug]) }}"
                           class="admin-sidebar__add"
                           title="Добавить в «{{ $label }}»"
                           aria-label="Добавить товар в раздел «{{ $label }}»">+</a>
                    </div>
                @endforeach

                <p class="admin-sidebar__group-title admin-sidebar__group-title--spaced">Сайт</p>
                <a href="{{ route('admin.hero-slides.edit') }}"
                   class="admin-sidebar__link @if(request()->routeIs('admin.hero-slides.*')) is-active @endif">
                    <span class="admin-sidebar__icon" aria-hidden="true">🖼</span>
                    <span class="admin-sidebar__label">Слайдер на главной</span>
                </a>

                <a href="{{ route('home') }}" class="admin-sidebar__link admin-sidebar__link--muted admin-sidebar__link--site">
                    <span class="admin-sidebar__icon" aria-hidden="true">↗</span>
                    На сайт
                </a>
            </nav>
            <div class="admin-sidebar__footer">
                <p class="admin-sidebar__user">{{ auth()->user()->email }}</p>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="admin-sidebar__logout">Выйти</button>
                </form>
            </div>
        </aside>

        <div class="admin-shell__main">
            <header class="admin-topbar">
                <button type="button" class="admin-topbar__menu" id="admin-menu-toggle" aria-controls="admin-sidebar" aria-expanded="false">
                    <span class="sr-only">Меню</span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </button>
                <div class="admin-topbar__title">@yield('title', 'Админ')</div>
                <label class="theme-switch admin-topbar__theme">
                    <span class="sr-only">Тёмная тема</span>
                    <span aria-hidden="true">Тема</span>
                    <input type="checkbox" id="theme-toggle" role="switch" aria-checked="false">
                    <span class="theme-switch__track" aria-hidden="true">
                        <span class="theme-switch__thumb"></span>
                    </span>
                </label>
            </header>

            <main class="admin-main">
                @if (request()->routeIs('admin.hero-slides.*') || ! request()->routeIs('admin.products.index') || $adminActiveCategory)
                    <nav class="admin-breadcrumb" aria-label="Навигация">
                        @if (request()->routeIs('admin.hero-slides.*'))
                            <span>
                                @hasSection('breadcrumb')
                                    @yield('breadcrumb')
                                @else
                                    @yield('title')
                                @endif
                            </span>
                        @else
                            <a href="{{ route('admin.products.index') }}">Товары</a>
                            @if ($adminActiveCategory)
                                <span aria-hidden="true">/</span>
                                <a href="{{ route('admin.products.index', ['category' => $adminActiveCategory]) }}">
                                    {{ $adminCategories[$adminActiveCategory] }}
                                </a>
                            @endif
                            @unless (request()->routeIs('admin.products.index'))
                                <span aria-hidden="true">/</span>
                                <span>
                                    @hasSection('breadcrumb')
                                        @yield('breadcrumb')
                                    @else
                                        @yield('title')
                                    @endif
                                </span>
                            @endunless
                        @endif
                    </nav>
                @endif

                @if (session('status'))
                    <div class="admin-toast admin-toast--ok" role="status" data-admin-toast>
                        <span>{{ session('status') }}</span>
                        <button type="button" class="admin-toast__close" aria-label="Закрыть">×</button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="admin-toast admin-toast--err" role="alert">
                        <ul>
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <div class="admin-sidebar-backdrop" id="admin-sidebar-backdrop" hidden></div>

    <script src="{{ asset('js/theme.js') }}" defer></script>
    <script src="{{ asset('js/admin.js') }}" defer></script>
</body>
</html>
