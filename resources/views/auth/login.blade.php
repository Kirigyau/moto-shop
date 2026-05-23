@extends('layouts.shop')

@section('title', 'Вход')

@section('content')
    <nav class="breadcrumbs" aria-label="Навигационная цепочка">
        <a href="{{ route('home') }}">Главная</a>
        <span aria-hidden="true"> / </span>
        <span aria-current="page">Вход</span>
    </nav>

    <h1 class="page-title">Вход</h1>

    <form method="post" action="{{ route('login.store') }}" class="auth-form">
        @csrf
        <label>
            Электронная почта или телефон
            <input type="text" name="login" value="{{ old('login') }}" required autocomplete="username" placeholder="admin, email@… или +7…">
        </label>
        <x-password-input name="password" label="Пароль" autocomplete="current-password" />
        <label class="auth-inline">
            <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
            Запомнить меня
        </label>
        <button type="submit" class="btn btn--primary">Войти</button>
        <p class="auth-switch">Нет аккаунта? <a href="{{ route('register') }}">Регистрация</a></p>
    </form>
@endsection
