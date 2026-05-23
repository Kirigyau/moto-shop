@extends('layouts.shop')

@section('title', $product->title)

@section('content')
    <nav class="breadcrumbs" aria-label="Навигационная цепочка">
        <a href="{{ route('home') }}">Главная</a>
        <span aria-hidden="true"> / </span>
        <a href="{{ route('catalog.category', $product->category) }}">{{ \App\Http\Controllers\ShopController::CATEGORIES[$product->category] ?? $product->category }}</a>
        <span aria-hidden="true"> / </span>
        <span aria-current="page">{{ $product->title }}</span>
    </nav>

    <article class="product-detail">
        <div class="product-detail__grid">
            <div class="product-detail__media">
                @if ($product->badge)
                    <span class="product-card__badge @if ($product->badge === 'Распродажа') product-card__badge--sale @endif">{{ $product->badge }}</span>
                @endif
                <img src="{{ $product->image_src }}" alt="" width="900" height="675" loading="eager">
            </div>
            <div class="product-detail__info">
                <h1 class="page-title product-detail__title">{{ $product->title }}</h1>
                @if ($product->brand)
                    <p class="product-detail__brand">Бренд: <strong>{{ $product->brand }}</strong></p>
                @endif
                <div class="product-card__prices product-detail__prices">
                    <span class="price-current">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
                    @if ($product->old_price)
                        <span class="price-old">{{ number_format($product->old_price, 0, ',', ' ') }} ₽</span>
                    @endif
                </div>
                <form method="post" action="{{ route('cart.add') }}" class="product-detail__buy">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <label class="qty-label">
                        Количество
                        <input type="number" name="qty" value="1" min="1" max="99" class="qty-input">
                    </label>
                    <button type="submit" class="btn btn--primary">В корзину</button>
                </form>
                @if ($product->description)
                    <div class="prose">
                        <h2>Описание</h2>
                        <p>{{ $product->description }}</p>
                    </div>
                @endif
                <div class="specs-block">
                    <h2>Характеристики</h2>
                    <ul class="specs-simple">
                        @foreach ($product->specs ?? [] as $spec)
                            <li>{{ $spec }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </article>
@endsection
