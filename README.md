# МотоШоп

Интернет-витрина на Laravel: каталог, корзина, оформление заказа, админка.

## Запуск

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan db:seed
php artisan serve
```

Статика: `public/css/shop.css`, `public/js/*` (сборка Vite не используется).

## Сжатие загруженных фото

После загрузки картинок в админке:

```bash
php artisan images:optimize
```

Нужно расширение PHP **GD**. На Windows: раскомментируйте `extension=gd` в `php.ini`.

## Админ

После сидов: логин `admin`, пароль `admin`.

## Railway (PostgreSQL)

Локально SQLite не трогаем. Для хостинга: [docs/railway.md](docs/railway.md), `railway.env.example`, `railway.toml`.
