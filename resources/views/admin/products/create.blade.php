@extends('layouts.admin')

@section('title', 'Новый товар')
@section('breadcrumb', 'Новый товар')

@section('content')
    <header class="admin-form-header">
        <h1 class="admin-page-title">Новый товар</h1>
        <p class="admin-page-desc">
            @if ($category)
                Раздел: <strong>{{ \App\Http\Controllers\ShopController::CATEGORIES[$category] }}</strong> — поле можно изменить в форме.
            @else
                Заполните поля и сохраните — товар появится в каталоге.
            @endif
        </p>
    </header>

    <form method="post" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="admin-form">
        @csrf
        @include('admin.products._form', ['product' => $product, 'specsRaw' => old('specs_raw', '')])
        <div class="admin-form-actions">
            <button type="submit" class="btn btn--primary">Создать товар</button>
            <a class="btn btn--ghost"
               href="{{ $category ? route('admin.products.index', ['category' => $category]) : route('admin.products.index') }}">
                Отмена
            </a>
        </div>
    </form>
@endsection
