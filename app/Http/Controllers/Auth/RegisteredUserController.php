<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Phone;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $phoneNorm = Phone::normalize($request->string('phone')->toString());
        if ($phoneNorm === null) {
            return back()->withErrors(['phone' => 'Введите корректный номер телефона в формате +7…'])->withInput();
        }

        if (User::query()->where('phone', $phoneNorm)->exists()) {
            return back()->withErrors(['phone' => 'Этот номер уже зарегистрирован.'])->withInput();
        }

        $validated = $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::query()->create([
            'name' => $validated['email'],
            'email' => $validated['email'],
            'phone' => $phoneNorm,
            'password' => Hash::make($request->string('password')->toString()),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('home')->with('status', 'Регистрация прошла успешно.');
    }
}
