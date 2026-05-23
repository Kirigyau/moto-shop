<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $state = Cart::getState();

        return view('shop.cart', [
            'lines' => $state['lines'],
            'totalQty' => $state['total_qty'],
            'totalSum' => $state['total_sum'],
        ]);
    }

    public function add(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty' => ['sometimes', 'integer', 'min:1', 'max:99'],
        ]);

        Cart::add((int) $data['product_id'], (int) ($data['qty'] ?? 1));

        return back()->with('status', 'Товар добавлен в корзину.');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        foreach ($data['items'] as $row) {
            Cart::setQty((int) $row['product_id'], (int) $row['qty']);
        }

        return redirect()->route('cart.index')->with('status', 'Корзина обновлена.');
    }

    public function remove(Request $request, Product $product): RedirectResponse
    {
        Cart::remove($product->id);

        return back()->with('status', 'Позиция удалена из корзины.');
    }
}
