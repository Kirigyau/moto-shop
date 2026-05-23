@props(['product', 'showCart' => true])

<article class="product-card">
    <div class="product-card__media">
        @if (!empty($product->badge))
            <span class="product-card__badge @if ($product->badge === 'Распродажа') product-card__badge--sale @endif">{{ $product->badge }}</span>
        @endif
        <a href="{{ route('product.show', $product) }}">
            <img src="{{ $product->image_src }}" alt="" loading="lazy" width="640" height="480">
        </a>
    </div>
    <div class="product-card__body">
        <h3 class="product-card__title">
            <a href="{{ route('product.show', $product) }}">{{ $product->title }}</a>
        </h3>
        <div class="product-card__prices">
            <span class="price-current">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
            @if ($product->old_price)
                <span class="price-old">{{ number_format($product->old_price, 0, ',', ' ') }} ₽</span>
                @php
                    $pct = $product->old_price > 0 ? round(100 - ($product->price / $product->old_price) * 100) : 0;
                @endphp
                @if ($pct > 0)
                    <span class="discount-pill">−{{ $pct }}%</span>
                @endif
            @endif
        </div>
        <div class="product-card__specs">
            @foreach ($product->specs ?? [] as $s)
                <span>{{ $s }}</span>
            @endforeach
        </div>
        <div class="product-card__actions">
            @if ($showCart)
                <form method="post" action="{{ route('cart.add') }}" class="product-card__form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="qty" value="1">
                    <button type="submit" class="btn btn--primary">В корзину</button>
                </form>
            @endif
            <a class="btn btn--ghost" href="{{ route('product.show', $product) }}">Подробнее</a>
        </div>
    </div>
</article>
