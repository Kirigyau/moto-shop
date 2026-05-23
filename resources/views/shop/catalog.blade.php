@extends('layouts.shop')

@section('title', $categoryTitle)

@section('content')
    <nav class="breadcrumbs" aria-label="Навигационная цепочка">
        <a href="{{ route('home') }}">Главная</a>
        <span aria-hidden="true"> / </span>
        <span aria-current="page">{{ $categoryTitle }}</span>
    </nav>

    <h1 class="page-title">{{ $categoryTitle }}</h1>

    <div class="catalog-layout">
        <aside class="filters" aria-labelledby="filters-heading">
            <details class="filters-drawer" id="catalog-filters-drawer" open>
                <summary class="filters-drawer__toggle">
                    <span class="filters-drawer__toggle-text">Фильтры</span>
                    <span class="filters-drawer__chevron" aria-hidden="true"></span>
                </summary>
                <div class="filters-drawer__body">
                    <h2 id="filters-heading" class="filters__title">Фильтры</h2>

                    <form id="catalog-filters-form" method="get" action="{{ route('catalog.category', $categoryKey) }}">
                        @if (request()->filled('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif

                        <details class="filter-block" @if (request()->filled('price_from') || request()->filled('price_to')) open @endif>
                            <summary>Цена, ₽</summary>
                            <div class="filter-block__content price-range">
                                <label class="sr-only" for="price-from">От</label>
                                <input id="price-from" type="number" name="price_from" placeholder="от" min="0" step="1000" value="{{ request('price_from') }}">
                                <label class="sr-only" for="price-to">До</label>
                                <input id="price-to" type="number" name="price_to" placeholder="до" min="0" step="1000" value="{{ request('price_to') }}">
                            </div>
                        </details>

                        @if ($subcategories->isNotEmpty())
                            <details class="filter-block" @if (request()->filled('subcategory')) open @endif>
                                <summary>Категории</summary>
                                <ul class="filter-list filter-block__content">
                                    <li>
                                        <label><input type="radio" name="subcategory" value="" @checked(!request('subcategory'))> Все</label>
                                    </li>
                                    @foreach ($subcategories as $sub)
                                        <li>
                                            <label><input type="radio" name="subcategory" value="{{ $sub }}" @checked(request('subcategory') === $sub)> {{ $sub }}</label>
                                        </li>
                                    @endforeach
                                </ul>
                            </details>
                        @endif

                        @if ($brands->isNotEmpty())
                            <details class="filter-block" @if (request()->filled('brand')) open @endif>
                                <summary>Бренды</summary>
                                <ul class="filter-list filter-block__content">
                                    @foreach ($brands as $brand)
                                        <li>
                                            <label>
                                                <input type="checkbox" name="brand[]" value="{{ $brand }}" @checked(in_array($brand, (array) request('brand', []), true))>
                                                {{ $brand }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </details>
                        @endif

                        @if ($showEngineFilter)
                            <details class="filter-block" @if (request()->filled('engine_type')) open @endif>
                                <summary>Тип двигателя</summary>
                                <ul class="filter-list filter-block__content">
                                    <li><label><input type="radio" name="engine_type" value="" @checked(!request('engine_type'))> Любой</label></li>
                                    <li><label><input type="radio" name="engine_type" value="gasoline" @checked(request('engine_type') === 'gasoline')> Бензиновый</label></li>
                                    <li><label><input type="radio" name="engine_type" value="electric" @checked(request('engine_type') === 'electric')> Электрический</label></li>
                                </ul>
                            </details>
                        @endif

                        <details class="filter-block" @if (request()->filled('sort') && request('sort') !== 'popular') open @endif>
                            <summary>Сортировка</summary>
                            <div class="filter-block__content">
                                @php($curSort = request('sort', 'popular'))
                                <select id="catalog-sort" name="sort" class="catalog-sort-select">
                                    <option value="popular" @selected($curSort === 'popular')>По популярности</option>
                                    <option value="price_desc" @selected($curSort === 'price_desc')>Подороже</option>
                                    <option value="price_asc" @selected($curSort === 'price_asc')>Подешевле</option>
                                    <option value="title" @selected($curSort === 'title')>По названию</option>
                                </select>
                            </div>
                        </details>

                        <div class="filter-actions">
                            <button type="submit" class="btn btn--primary">Применить</button>
                            <a class="btn btn--ghost" href="{{ route('catalog.category', $categoryKey) }}">Сбросить</a>
                        </div>
                    </form>
                </div>
            </details>
            <script>
                (function () {
                    if (!window.matchMedia('(max-width: 900px)').matches) {
                        return;
                    }
                    var drawer = document.getElementById('catalog-filters-drawer');
                    if (!drawer) {
                        return;
                    }
                    var hasActive = {{ request()->hasAny(['price_from', 'price_to', 'subcategory', 'brand', 'engine_type']) || (request()->filled('sort') && request('sort') !== 'popular') ? 'true' : 'false' }};
                    if (!hasActive) {
                        drawer.removeAttribute('open');
                    }
                })();
            </script>
        </aside>

        <section class="catalog-main" aria-labelledby="catalog-heading">
            <h2 id="catalog-heading" class="sr-only">Список товаров</h2>

            <div class="toolbar">
                <span class="toolbar-meta">Найдено позиций: {{ $products->total() }}</span>
            </div>

            <div class="product-grid">
                @forelse ($products as $product)
                    <x-product-card :product="$product" />
                @empty
                    <p class="catalog-empty">Ничего не найдено. Измените фильтры или поисковый запрос.</p>
                @endforelse
            </div>

            @if ($products->hasPages())
                <div class="pagination-wrap">
                    {{ $products->links('pagination::shop-simple') }}
                </div>
            @endif
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ public_asset('js/catalog-filters.js') }}" defer></script>
@endpush
