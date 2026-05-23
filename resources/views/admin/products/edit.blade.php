@extends('layouts.admin')

@section('title', 'Редактирование')
@section('breadcrumb', $product->title)

@section('content')
    <header class="admin-form-header">
        <h1 class="admin-page-title">Редактировать товар</h1>
        <p class="admin-page-desc">
            <a href="{{ route('product.show', $product) }}" target="_blank" rel="noopener">Открыть на сайте ↗</a>
        </p>
    </header>

    <form method="post" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="admin-form">
        @csrf
        @method('PUT')
        @include('admin.products._form', ['product' => $product, 'specsRaw' => old('specs_raw', $specsRaw)])
        <div class="admin-form-actions">
            <button type="submit" class="btn btn--primary">Сохранить изменения</button>
            <a class="btn btn--ghost" href="{{ route('admin.products.index', ['category' => $product->category]) }}">К списку</a>
        </div>
    </form>
@endsection
