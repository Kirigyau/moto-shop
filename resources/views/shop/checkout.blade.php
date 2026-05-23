@extends('layouts.shop')

@section('title', 'Оформление заказа')

@section('content')
    <div class="checkout-page">
    <nav class="breadcrumbs" aria-label="Навигационная цепочка">
        <a href="{{ route('home') }}">Главная</a>
        <span aria-hidden="true"> / </span>
        <a href="{{ route('cart.index') }}">Корзина</a>
        <span aria-hidden="true"> / </span>
        <span aria-current="page">Оформление</span>
    </nav>

    <header class="checkout-header">
        <h1 class="page-title checkout-header__title">Оформление заказа</h1>
        <p class="checkout-header__lead">Заполните контакты и адрес — мы перезвоним для подтверждения и уточнения доставки.</p>
    </header>

    <div class="checkout-grid">
        <section class="checkout-card checkout-card--summary" aria-labelledby="checkout-sum-title">
            <h2 id="checkout-sum-title" class="checkout-card__title">Ваш заказ</h2>
            <ul class="checkout-lines">
                @foreach ($lines as $line)
                    @php($p = $line['product'])
                    <li class="checkout-line">
                        <a class="checkout-line__thumb{{ $p->image_src ? '' : ' checkout-line__thumb--empty' }}" href="{{ route('product.show', $p) }}" tabindex="-1" aria-hidden="true">
                            @if ($p->image_src)
                                <img src="{{ $p->image_src }}" alt="" width="72" height="54" loading="lazy">
                            @endif
                        </a>
                        <div class="checkout-line__body">
                            <a class="checkout-line__title" href="{{ route('product.show', $p) }}">{{ $p->title }}</a>
                            <span class="checkout-line__meta">{{ $line['qty'] }} × {{ number_format($p->price, 0, ',', ' ') }} ₽</span>
                        </div>
                        <span class="checkout-line__sum">{{ number_format($line['line_total'], 0, ',', ' ') }} ₽</span>
                    </li>
                @endforeach
            </ul>
            <div class="checkout-summary-footer">
                <span class="checkout-summary-footer__label">Итого</span>
                <span class="checkout-summary-footer__sum">{{ number_format($totalSum, 0, ',', ' ') }} ₽</span>
                <span class="checkout-summary-footer__qty">{{ $totalQty }} {{ $totalQty === 1 ? 'товар' : ($totalQty < 5 ? 'товара' : 'товаров') }}</span>
            </div>
        </section>

        <section class="checkout-card checkout-card--form" aria-labelledby="checkout-form-title">
            <h2 id="checkout-form-title" class="checkout-card__title">Куда доставить</h2>
            <form id="checkout-form" method="post" action="{{ route('checkout.store') }}" class="checkout-form" novalidate>
                @csrf
                <div class="checkout-form__row checkout-form__row--2">
                    <div class="form-field @error('customer_name') form-field--invalid @enderror">
                        <label for="customer_name">ФИО</label>
                        <input id="customer_name" type="text" name="customer_name" value="{{ old('customer_name') }}" required autocomplete="name" maxlength="255">
                        @error('customer_name')
                            <span class="form-field__error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-field @error('phone') form-field--invalid @enderror">
                        <label for="phone">Телефон</label>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel" maxlength="32" placeholder="+7 (900) 000-00-00" inputmode="tel">
                        @error('phone')
                            <span class="form-field__error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-field @error('email') form-field--invalid @enderror">
                    <label for="email">Электронная почта <span class="form-field__optional">необязательно</span></label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" maxlength="255" placeholder="name@example.com">
                    @error('email')
                        <span class="form-field__error" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-field @error('address') form-field--invalid @enderror">
                    <label for="address">Адрес доставки</label>
                    <textarea id="address" name="address" rows="3" required maxlength="500" placeholder="Город, улица, дом, квартира">{{ old('address') }}</textarea>
                    @error('address')
                        <span class="form-field__error" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-field @error('comment') form-field--invalid @enderror">
                    <label for="comment">Комментарий к заказу</label>
                    <textarea id="comment" name="comment" rows="2" maxlength="2000" placeholder="Удобное время, подъезд, домофон…">{{ old('comment') }}</textarea>
                    @error('comment')
                        <span class="form-field__error" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="checkout-actions">
                    <button type="submit" class="btn btn--primary btn--block checkout-actions__submit">Подтвердить заказ</button>
                    <a class="btn btn--ghost btn--block" href="{{ route('cart.index') }}">Вернуться в корзину</a>
                </div>
            </form>
        </section>
    </div>

    <div class="checkout-mobile-bar" aria-label="Быстрое оформление">
        <div class="checkout-mobile-bar__info">
            <span class="checkout-mobile-bar__label">Итого</span>
            <strong class="checkout-mobile-bar__sum">{{ number_format($totalSum, 0, ',', ' ') }} ₽</strong>
        </div>
        <button type="submit" form="checkout-form" class="btn btn--primary checkout-mobile-bar__submit">Подтвердить</button>
    </div>
    </div>
@endsection
