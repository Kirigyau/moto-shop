#!/bin/sh
set -eu

# На Railway переменные доступны только в runtime; не кэшируем конфиг с localhost.
php artisan config:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
