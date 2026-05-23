@php
    /** @var \App\Models\Product $product */
@endphp

<div class="admin-form-grid">
    <section class="admin-form-card">
        <h2 class="admin-form-card__title">Каталог</h2>
        <div class="admin-form-card__body">
            <label>
                Раздел
                <select name="category" required>
                    @foreach (['mototekhnika' => 'Мототехника', 'ekipirovka' => 'Экипировка', 'zapchasti' => 'Запчасти'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('category', $product->category) === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>

            <label>
                Подкатегория (для фильтра)
                <input type="text" name="subcategory" value="{{ old('subcategory', $product->subcategory) }}" placeholder="Например: Шлемы">
            </label>

            <div class="admin-form-row">
                <label>
                    Бренд
                    <input type="text" name="brand" value="{{ old('brand', $product->brand) }}">
                </label>
                <label>
                    Тип двигателя
                    <select name="engine_type">
                        <option value="">—</option>
                        <option value="gasoline" @selected(old('engine_type', $product->engine_type) === 'gasoline')>Бензин</option>
                        <option value="electric" @selected(old('engine_type', $product->engine_type) === 'electric')>Электро</option>
                    </select>
                </label>
            </div>
        </div>
    </section>

    <section class="admin-form-card">
        <h2 class="admin-form-card__title">Название и цены</h2>
        <div class="admin-form-card__body">
            <label>
                Название
                <input type="text" name="title" value="{{ old('title', $product->title) }}" required maxlength="255">
            </label>

            <label>
                ЧПУ (slug)
                <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" placeholder="авто из названия, если пусто">
                <span class="admin-field-hint">Латиница, дефисы. Оставьте пустым для автогенерации.</span>
            </label>

            <div class="admin-form-row">
                <label>
                    Цена, ₽
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0">
                </label>
                <label>
                    Старая цена, ₽
                    <input type="number" name="old_price" value="{{ old('old_price', $product->old_price) }}" min="0">
                </label>
            </div>

            <label>
                Бейдж
                <input type="text" name="badge" value="{{ old('badge', $product->badge) }}" maxlength="64" placeholder="Хит, Распродажа…">
            </label>
        </div>
    </section>

    <section class="admin-form-card admin-form-card--wide">
        <h2 class="admin-form-card__title">Изображение</h2>
        <div class="admin-form-card__body">
            @if ($product->exists && $product->image)
                <div class="admin-current-image">
                    <span class="admin-current-image__label">Текущее изображение</span>
                    <img src="{{ $product->image_src }}" alt="" width="200" height="150" loading="lazy">
                </div>
            @endif

            <label>
                Ссылка на изображение
                <input type="text" name="image" value="{{ old('image', $product->image) }}" placeholder="https://… или /storage/…">
            </label>

            <label class="admin-file-label">
                <span>Загрузить файл</span>
                <input type="file" name="image_upload" accept="image/*">
            </label>
        </div>
    </section>

    <section class="admin-form-card admin-form-card--wide">
        <h2 class="admin-form-card__title">Описание</h2>
        <div class="admin-form-card__body">
            <label>
                Текст описания
                <textarea name="description" rows="5">{{ old('description', $product->description) }}</textarea>
            </label>

            <label>
                Характеристики
                <textarea name="specs_raw" rows="8" placeholder="Объём 250 см³&#10;Мощность 15 л.с.">{{ old('specs_raw', $specsRaw) }}</textarea>
                <span class="admin-field-hint">Каждая строка — отдельный пункт в карточке товара.</span>
            </label>
        </div>
    </section>
</div>
