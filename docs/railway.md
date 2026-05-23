# Деплой на Railway

Локальная разработка **не меняется**: `.env` + SQLite (`DB_CONNECTION=sqlite`).

## Файлы в репозитории

| Файл | Назначение |
|------|------------|
| `railway.toml` / `railway.json` | Config as Code (pre-deploy, start) |
| `railway.yaml` | То же в YAML (справочно; Railway читает toml/json) |
| `railway.env.example` | Переменные для вставки в панель Railway |
| `scripts/railway-predeploy.sh` | Миграции + идемпотентный сид |
| `database/seeders/DeploySeeder.php` | Сид для продакшена |

## Шаги

1. Залейте репозиторий на GitHub и подключите к Railway.
2. Добавьте плагин **PostgreSQL**.
3. В сервисе приложения → **Variables** скопируйте блок из `railway.env.example`.
4. Укажите `APP_KEY`, `APP_URL`, привяжите `DB_URL=${{Postgres.DATABASE_URL}}`.
5. **Deploy** — перед стартом выполнятся миграции и `DeploySeeder`.
6. **APP_URL** — в Variables укажите `https://${{RAILWAY_PUBLIC_DOMAIN}}` (см. `railway.env.example`). Без https стили и скрипты могут не загружаться.

## Стили / картинки на проде

- CSS и JS лежат в `public/css`, `public/js` и подключаются через `public_asset()` (относительные пути).
- Картинки товаров в сиде — внешние URL (Unsplash). Загруженные в админке файлы — `/storage/...`; нужен `storage:link` (выполняется в preDeploy).
- Предупреждение Docker `SecretsUsedInArgOrEnv` про `APP_KEY` на этапе сборки — от Nixpacks, на работу сайта не влияет. `APP_KEY` задавайте только в Variables Railway, не в репозитории.

## Идемпотентный сид

`DeploySeeder` не очищает таблицы. Товары обновляются по `slug`, админ — по `email` `admin`.

Повторный деплой безопасен для данных в PostgreSQL на Railway.

## Volume (загрузка фото в админке)

Без Volume загруженные файлы **пропадают при каждом redeploy**.

1. В сервисе Laravel: **Volumes** → Add Volume.
2. Mount path: `/app/storage/app/public`
3. После деплоя один раз: `php artisan storage:link` (выполняется в `ensure-storage.sh` автоматически).

Сид `DeploySeeder` **не затирает** ваши загрузки (`products/…` на диске), только демо-URL Unsplash.
