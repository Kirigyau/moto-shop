<?php

namespace App\Support;

use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

final class Cart
{
    private static function row(): ShoppingCart
    {
        if (Auth::check()) {
            return ShoppingCart::query()->firstOrCreate(
                ['user_id' => Auth::id()],
                ['token' => null, 'items' => []]
            );
        }

        $token = request()->attributes->get('cart_token')
            ?? request()->cookie('cart_token');

        if (! $token || ! ShoppingCart::query()->where('token', $token)->exists()) {
            $token = Str::random(48);
            ShoppingCart::query()->create([
                'token' => $token,
                'user_id' => null,
                'items' => [],
            ]);
            Cookie::queue(Cookie::make(
                'cart_token',
                $token,
                60 * 24 * 400,
                '/',
                null,
                (bool) config('session.secure', false),
                true,
                false,
                config('session.same_site') ?: 'lax'
            ));
            request()->attributes->set('cart_token', $token);
        }

        return ShoppingCart::query()->firstOrCreate(
            ['token' => $token],
            ['user_id' => null, 'items' => []]
        );
    }

    /**
     * @return array<int, int> product_id => qty
     */
    public static function items(): array
    {
        $raw = self::row()->items ?? [];
        if (! is_array($raw)) {
            return [];
        }

        $out = [];
        foreach ($raw as $id => $qty) {
            $out[(int) $id] = (int) $qty;
        }

        return $out;
    }

    public static function count(): int
    {
        return array_sum(self::items());
    }

    /**
     * @return array{lines: Collection<int, array{product: Product, qty: int, line_total: int}>, total_qty: int, total_sum: int}
     */
    public static function getState(): array
    {
        $raw = self::items();
        if ($raw === []) {
            return [
                'lines' => collect(),
                'total_qty' => 0,
                'total_sum' => 0,
            ];
        }

        $products = Product::query()->whereIn('id', array_keys($raw))->get()->keyBy('id');
        $lines = collect();
        $totalQty = 0;
        $totalSum = 0;

        foreach ($raw as $id => $qty) {
            $product = $products->get($id);
            if (! $product || $qty < 1) {
                continue;
            }
            $lineTotal = $product->price * $qty;
            $lines->push([
                'product' => $product,
                'qty' => $qty,
                'line_total' => $lineTotal,
            ]);
            $totalQty += $qty;
            $totalSum += $lineTotal;
        }

        return [
            'lines' => $lines,
            'total_qty' => $totalQty,
            'total_sum' => $totalSum,
        ];
    }

    public static function add(int $productId, int $qty = 1): void
    {
        $qty = max(1, min(99, $qty));
        $cart = self::row();
        $items = $cart->items ?? [];
        if (! is_array($items)) {
            $items = [];
        }
        $items[$productId] = ($items[$productId] ?? 0) + $qty;
        $items[$productId] = min(99, (int) $items[$productId]);
        $cart->update(['items' => $items]);
    }

    public static function setQty(int $productId, int $qty): void
    {
        $cart = self::row();
        $items = $cart->items ?? [];
        if (! is_array($items)) {
            $items = [];
        }
        if ($qty < 1) {
            unset($items[$productId]);
        } else {
            $items[$productId] = min(99, $qty);
        }
        $cart->update(['items' => $items]);
    }

    public static function remove(int $productId): void
    {
        $cart = self::row();
        $items = $cart->items ?? [];
        if (! is_array($items)) {
            $items = [];
        }
        unset($items[$productId]);
        $cart->update(['items' => $items]);
    }

    public static function clear(): void
    {
        self::row()->update(['items' => []]);
    }

    /**
     * @param  array<int, int>  $guest
     * @param  array<int, int>  $user
     * @return array<int, int>
     */
    public static function mergeItemMaps(array $guest, array $user): array
    {
        $merged = $user;
        foreach ($guest as $id => $qty) {
            $id = (int) $id;
            $qty = (int) $qty;
            if ($qty < 1) {
                continue;
            }
            $merged[$id] = min(99, ($merged[$id] ?? 0) + $qty);
        }

        return $merged;
    }
}
