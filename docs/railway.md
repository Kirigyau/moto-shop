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

## Идемпотентный сид

`DeploySeeder` не очищает таблицы. Товары обновляются по `slug`, админ — по `email` `admin`.

Повторный деплой безопасен для данных в PostgreSQL на Railway.

## Volume (загрузка фото)

Для сохранения картинок между деплоями смонтируйте Volume на `/app/storage/app/public`.
