@extends('layouts.admin')

@section('title', $category ? ($categories[$category] ?? 'Товары') : 'Товары')

@section('content')
    <div class="admin-page-head">
        <div>
            <h1 class="admin-page-title">
                @if ($category)
                    {{ $categories[$category] }}
                @else
                    Все товары
                @endif
            </h1>
            <p class="admin-page-desc">
                @if ($category)
                    Товары раздела «{{ $categories[$category] }}»
                @else
                    Управление каталогом магазина
                @endif
            </p>
        </div>
        <a class="btn btn--primary"
           href="{{ $category ? route('admin.products.create', ['category' => $category]) : route('admin.products.create') }}">
            <span aria-hidden="true">+</span>
            @if ($category)
                Добавить в «{{ $categories[$category] }}»
            @else
                Добавить товар
            @endif
        </a>
    </div>

    <div class="admin-stats">
        <article class="admin-stat-card">
            <span class="admin-stat-card__label">
                @if ($category) В разделе @else Всего товаров @endif
            </span>
            <strong class="admin-stat-card__value">{{ $products->total() }}</strong>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-card__label">На странице</span>
            <strong class="admin-stat-card__value">{{ $products->count() }}</strong>
        </article>
    </div>

    @if ($products->isEmpty())
        <div class="admin-empty">
            <p class="admin-empty__title">
                @if ($category)
                    В разделе пока нет товаров
                @else
                    Пока нет товаров
                @endif
            </p>
            <p class="admin-empty__text">
                @if ($category)
                    Добавьте первый товар в «{{ $categories[$category] }}» — он сразу появится в каталоге на сайте.
                @else
                    Создайте первый товар или выберите раздел в меню слева.
                @endif
            </p>
            <a class="btn btn--primary"
               href="{{ $category ? route('admin.products.create', ['category' => $category]) : route('admin.products.create') }}">
                @if ($category)
                    Добавить в «{{ $categories[$category] }}»
                @else
                    Добавить товар
                @endif
            </a>
        </div>
    @else
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="admin-table__col-thumb"></th>
                        <th>Товар</th>
                        @unless ($category)
                            <th>Раздел</th>
                        @endunless
                        <th>Цена</th>
                        <th class="admin-table__col-actions">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>
                                @if ($product->image_src)
                                    <img class="admin-table__thumb" src="{{ $product->image_src }}" alt="" width="48" height="48" loading="lazy">
                                @else
                                    <span class="admin-table__thumb admin-table__thumb--empty" aria-hidden="true">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="admin-table__product">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="admin-table__name">{{ $product->title }}</a>
                                    <span class="admin-table__meta">#{{ $product->id }} · {{ $product->slug }}</span>
                                    @if ($product->badge)
                                        <span class="admin-badge">{{ $product->badge }}</span>
                                    @endif
                                </div>
                            </td>
                            @unless ($category)
                                <td>
                                    <span class="admin-chip">{{ $categories[$product->category] ?? $product->category }}</span>
                                </td>
                            @endunless
                            <td class="admin-table__price">{{ number_format($product->price, 0, ',', ' ') }} ₽</td>
                            <td>
                                <div class="admin-actions">
                                    <a class="btn btn--ghost btn--sm" href="{{ route('admin.products.edit', $product) }}">Изменить</a>
                                    <form method="post" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Удалить товар «{{ $product->title }}»?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn--ghost btn--sm btn--danger">Удалить</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $products->links('pagination::shop-simple') }}
    @endif
@endsection
