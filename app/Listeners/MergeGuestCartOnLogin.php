<?php

namespace App\Listeners;

use App\Models\ShoppingCart;
use App\Support\Cart;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Cookie;

class MergeGuestCartOnLogin
{
    public function handle(Login $event): void
    {
        $token = request()->cookie('cart_token');
        if (! $token) {
            return;
        }

        $guest = ShoppingCart::query()->where('token', $token)->first();
        if (! $guest) {
            Cookie::queue(Cookie::forget('cart_token'));

            return;
        }

        $guestItems = is_array($guest->items) ? $guest->items : [];
        if ($guestItems === []) {
            $guest->delete();
            Cookie::queue(Cookie::forget('cart_token'));

            return;
        }

        $userCart = ShoppingCart::query()->firstOrCreate(
            ['user_id' => $event->user->id],
            ['token' => null, 'items' => []]
        );
        $userItems = is_array($userCart->items) ? $userCart->items : [];
        $userCart->update(['items' => Cart::mergeItemMaps($guestItems, $userItems)]);
        $guest->delete();
        Cookie::queue(Cookie::forget('cart_token'));
    }
}
