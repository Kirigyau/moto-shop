@extends('layouts.admin')

@section('title', 'Слайдер на главной')
@section('breadcrumb', 'Слайдер на главной')

@section('content')
    <header class="admin-form-header">
        <h1 class="admin-page-title">Рекламный слайдер</h1>
        <p class="admin-page-desc">
            До {{ \App\Models\HeroSlide::MAX_SLIDES }} фото для блока на главной странице. Смена каждые 10 секунд с плавным переходом.
        </p>
    </header>

    <form method="post" action="{{ route('admin.hero-slides.update') }}" enctype="multipart/form-data" class="admin-form">
        @csrf
        @method('PUT')

        <div class="admin-form-grid admin-form-grid--slides">
            @for ($slot = 1; $slot <= \App\Models\HeroSlide::MAX_SLIDES; $slot++)
                @php $slide = $slides->get($slot); @endphp
                <section class="admin-form-card">
                    <h2 class="admin-form-card__title">Слайд {{ $slot }}</h2>
                    <div class="admin-form-card__body">
                        @if ($slide)
                            <div class="admin-current-image">
                                <span class="admin-current-image__label">Текущее фото</span>
                                <img src="{{ $slide->image_src }}" alt="" width="280" height="160" loading="lazy">
                            </div>
                            <label class="admin-inline-check">
                                <input type="checkbox" name="slots[{{ $slot }}][remove]" value="1">
                                Удалить этот слайд
                            </label>
                        @endif

                        <label>
                            Ссылка на изображение
                            <input type="text" name="slots[{{ $slot }}][image]" value="{{ old("slots.{$slot}.image", $slide->image ?? '') }}" placeholder="https://… или /storage/…">
                        </label>

                        <label class="admin-file-label">
                            <span>Загрузить файл</span>
                            <input type="file" name="slots[{{ $slot }}][image_upload]" accept="image/*">
                        </label>

                        <label>
                            Подпись (alt)
                            <input type="text" name="slots[{{ $slot }}][alt]" value="{{ old("slots.{$slot}.alt", $slide->alt ?? '') }}" maxlength="255" placeholder="Описание для доступности">
                        </label>

                        <label>
                            Ссылка при клике (необязательно)
                            <input type="text" name="slots[{{ $slot }}][link]" value="{{ old("slots.{$slot}.link", $slide->link ?? '') }}" maxlength="2048" placeholder="/catalog/mototekhnika или https://…">
                        </label>
                    </div>
                </section>
            @endfor
        </div>

        <div class="admin-form-actions">
            <button type="submit" class="btn btn--primary">Сохранить слайдер</button>
        </div>
    </form>
@endsection
