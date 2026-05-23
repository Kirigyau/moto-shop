@extends('layouts.shop')

@section('title', 'Заказ оформлен')

@section('content')
    <nav class="breadcrumbs" aria-label="Навигационная цепочка">
        <a href="{{ route('home') }}">Главная</a>
        <span aria-hidden="true"> / </span>
        <span aria-current="page">Заказ</span>
    </nav>

    <h1 class="page-title">Спасибо за заказ</h1>
    <p class="checkout-thanks-text">Мы получили вашу заявку и свяжемся с вами для уточнения деталей и подтверждения.</p>
    <p><a class="btn btn--primary" href="{{ route('home') }}">На главную</a></p>
@endsection
