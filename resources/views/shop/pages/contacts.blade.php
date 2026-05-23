@extends('layouts.shop')

@section('title', 'Контакты')

@section('content')
    <nav class="breadcrumbs" aria-label="Навигационная цепочка">
        <a href="{{ route('home') }}">Главная</a>
        <span aria-hidden="true"> / </span>
        <span aria-current="page">Контакты</span>
    </nav>

    <h1 class="page-title">Контакты</h1>

    <div class="prose prose--page">
        <p><strong>МотоШоп</strong> — мотомагазин в Чебоксарах.</p>
        <ul>
            <li><strong>Адрес:</strong> г. Чебоксары, ул. Декабристов, 17А</li>
            <li><strong>Телефон:</strong> <a href="tel:+79000000000">+7 (900) 000-00-00</a></li>
            <li><strong>Почта:</strong> <a href="mailto:insezik510@gmail.com">insezik510@gmail.com</a></li>
            <li><strong>Часы работы:</strong> ежедневно 9:00–18:00</li>
        </ul>
        <p>Звонки и письма обрабатываются в рабочее время. Для навигаторов можно использовать поиск по адресу: улица Декабристов, 17А, Чебоксары.</p>
    </div>
@endsection
