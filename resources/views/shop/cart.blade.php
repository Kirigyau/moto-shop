@extends('layouts.shop')

@section('title', 'Корзина')

@section('content')
    <nav class="breadcrumbs" aria-label="Навигационная цепочка">
        <a href="{{ route('home') }}">Главная</a>
        <span aria-hidden="true"> / </span>
        <span aria-current="page">Корзина</span>
    </nav>

    <h1 class="page-title">Корзина</h1>

    @if ($lines->isEmpty())
        <p class="cart-empty">В корзине пока ничего нет. Перейдите в <a href="{{ route('catalog.category', 'mototekhnika') }}">каталог</a>.</p>
    @else
        <form method="post" action="{{ route('cart.update') }}" class="cart-form">
            @csrf
            <div class="cart-table-wrap">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Товар</th>
                            <th>Цена</th>
                            <th>Кол-во</th>
                            <th>Сумма</th>
                            <th><span class="sr-only">Действия</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lines as $line)
                            @php($p = $line['product'])
                            <tr class="cart-row">
                                <td data-label="Товар">
                                    <a class="cart-row__title" href="{{ route('product.show', $p) }}">{{ $p->title }}</a>
                                </td>
                                <td data-label="Цена">{{ number_format($p->price, 0, ',', ' ') }} ₽</td>
                                <td data-label="Кол-во">
                                    <input class="qty-input" type="number" name="items[{{ $loop->index }}][qty]" value="{{ $line['qty'] }}" min="0" max="99" inputmode="numeric">
                                    <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $p->id }}">
                                </td>
                                <td data-label="Сумма" class="cart-row__line-total">{{ number_format($line['line_total'], 0, ',', ' ') }} ₽</td>
                                <td data-label="">
                                    <button type="submit" class="link-button cart-row__remove" formaction="{{ route('cart.remove', $p) }}" formmethod="post">Удалить</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="cart-summary">
                <p class="cart-total">Итого: <strong>{{ number_format($totalSum, 0, ',', ' ') }} ₽</strong> ({{ $totalQty }} шт.)</p>
                <div class="cart-actions-row">
                    <button type="submit" class="btn btn--primary btn--block-sm">Пересчитать корзину</button>
                    <a class="btn btn--ghost btn--block-sm" href="{{ route('checkout.create') }}">Заказать</a>
                </div>
            </div>
        </form>
    @endif
@endsection
