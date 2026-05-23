<?php

namespace App\Http\Middleware;

use App\Models\ShoppingCart;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EnsureShoppingCart
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            return $next($request);
        }

        $token = $request->cookie('cart_token');
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
        }

        $request->attributes->set('cart_token', $token);

        return $next($request);
    }
}
