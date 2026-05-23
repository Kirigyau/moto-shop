@extends('layouts.shop')

@section('title', 'Регистрация')

@section('content')
    <nav class="breadcrumbs" aria-label="Навигационная цепочка">
        <a href="{{ route('home') }}">Главная</a>
        <span aria-hidden="true"> / </span>
        <span aria-current="page">Регистрация</span>
    </nav>

    <h1 class="page-title">Регистрация</h1>

    <form method="post" action="{{ route('register.store') }}" class="auth-form">
        @csrf
        <label>
            Телефон
            <input type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel" placeholder="+7 (900) 123-45-67">
        </label>
        <label>
            Электронная почта
            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
        </label>
        <x-password-input name="password" label="Пароль" autocomplete="new-password" />
        <x-password-input name="password_confirmation" id="password_confirmation" label="Повторите пароль" autocomplete="new-password" />
        <button type="submit" class="btn btn--primary">Зарегистрироваться</button>
        <p class="auth-switch">Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></p>
    </form>
@endsection
