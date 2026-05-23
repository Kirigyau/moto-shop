@extends('layouts.shop')

@section('title', 'Поиск')

@section('content')
    <nav class="breadcrumbs" aria-label="Навигационная цепочка">
        <a href="{{ route('home') }}">Главная</a>
        <span aria-hidden="true"> / </span>
        <span aria-current="page">Поиск</span>
    </nav>

    <h1 class="page-title">Поиск по каталогу</h1>

    <form class="search search--page" method="get" action="{{ route('search') }}" role="search">
        <label class="sr-only" for="q2">Запрос</label>
        <input id="q2" name="q" type="search" value="{{ $q }}" placeholder="Например: скутер, шлем, масло…">
        <button type="submit">Найти</button>
    </form>

    <p class="search-hint">Найдено позиций: {{ $products->total() }}</p>

    <div class="product-grid" style="margin-top:1.5rem">
        @forelse ($products as $product)
            <x-product-card :product="$product" />
        @empty
            <p class="catalog-empty">Введите запрос или измените формулировку.</p>
        @endforelse
    </div>

    @if ($products->hasPages())
        <div class="pagination-wrap">
            {{ $products->links('pagination::shop-simple') }}
        </div>
    @endif
@endsection
