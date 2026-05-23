<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Phone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $login = $request->string('login')->toString();
        $password = $request->string('password')->toString();

        $user = null;
        if (str_contains($login, '@')) {
            $user = User::query()->where('email', $login)->first();
        } else {
            $phone = Phone::normalize($login);
            if ($phone !== null) {
                $user = User::query()->where('phone', $phone)->first();
            }
            if ($user === null) {
                $user = User::query()->where('email', $login)->first();
            }
        }

        if (! $user || ! Hash::check($password, $user->password)) {
            return back()->withErrors([
                'login' => 'Неверный логин или пароль.',
            ])->onlyInput('login');
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Вы вышли из аккаунта.');
    }
}
