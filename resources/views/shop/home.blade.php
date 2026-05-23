@extends('layouts.shop')

@section('title', 'Главная')

@section('content')
    <nav class="breadcrumbs" aria-label="Навигационная цепочка">
        <span aria-current="page">Главная</span>
    </nav>

    <section class="hero">
        <div>
            <h1>Мототехника и экипировка в Чебоксарах</h1>
            <p>
                Интернет-витрина «МотоШоп»: каталог мототехники, экипировки и запчастей с фильтрами, карточками товаров и оформлением заказа в корзине.
            </p>
            <div class="hero__cta">
                <a class="btn btn--primary" href="{{ route('catalog.category', 'mototekhnika') }}">В каталог мототехники</a>
                <a class="btn btn--ghost" href="{{ route('catalog.category', 'ekipirovka') }}">Экипировка</a>
            </div>
        </div>
        <div class="hero__visual">
            @if ($heroSlides->isNotEmpty())
                <div class="hero-slider" data-hero-slider data-interval="10000">
                    @foreach ($heroSlides as $index => $slide)
                        <div class="hero-slider__slide @if ($index === 0) is-active @endif" data-hero-slide>
                            @if ($slide->link)
                                <a href="{{ $slide->link }}" class="hero-slider__link">
                                    <img src="{{ $slide->image_hero_src }}" width="900" height="520" alt="{{ $slide->alt ?? 'Реклама' }}" @if ($index === 0) loading="eager" fetchpriority="high" @else loading="lazy" @endif decoding="async">
                                </a>
                            @else
                                <img src="{{ $slide->image_hero_src }}" width="900" height="520" alt="{{ $slide->alt ?? 'Реклама' }}" @if ($index === 0) loading="eager" fetchpriority="high" @else loading="lazy" @endif decoding="async">
                            @endif
                        </div>
                    @endforeach
                    @if ($heroSlides->count() > 1)
                        <div class="hero-slider__dots" aria-hidden="true">
                            @foreach ($heroSlides as $index => $slide)
                                <span class="hero-slider__dot @if ($index === 0) is-active @endif" data-hero-dot></span>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="hero-slider hero-slider--empty" aria-hidden="true"></div>
            @endif
        </div>
    </section>

    <div class="section-head">
        <h2>Хиты каталога</h2>
        <a href="{{ route('catalog.category', 'mototekhnika') }}">Все модели →</a>
    </div>

    <div class="product-grid">
        @foreach ($featured as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/hero-slider.js') }}" defer></script>
@endpush
