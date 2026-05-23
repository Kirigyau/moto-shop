<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Support\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(): View|RedirectResponse
    {
        $state = Cart::getState();
        if ($state['lines']->isEmpty()) {
            return redirect()->route('cart.index')->with('status', 'Сначала добавьте товары в корзину.');
        }

        return view('shop.checkout', [
            'lines' => $state['lines'],
            'totalQty' => $state['total_qty'],
            'totalSum' => $state['total_sum'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $state = Cart::getState();
        if ($state['lines']->isEmpty()) {
            return redirect()->route('cart.index')->with('status', 'Корзина пуста.');
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($data, $state): void {
            $order = Order::query()->create([
                'customer_name' => $data['customer_name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?: null,
                'address' => $data['address'],
                'comment' => $data['comment'] ?? null,
                'total_sum' => $state['total_sum'],
                'total_qty' => $state['total_qty'],
            ]);

            foreach ($state['lines'] as $line) {
                $p = $line['product'];
                $order->items()->create([
                    'product_id' => $p->id,
                    'title' => $p->title,
                    'unit_price' => $p->price,
                    'qty' => $line['qty'],
                    'line_total' => $line['line_total'],
                ]);
            }
        });

        Cart::clear();

        return redirect()->route('checkout.thanks')->with('status', 'Заказ принят. Мы свяжемся с вами для подтверждения.');
    }

    public function thanks(): View
    {
        return view('shop.checkout-thanks');
    }
}
