#!/bin/sh
set -eu

mkdir -p storage/app/public/products storage/app/public/hero
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views
mkdir -p storage/logs
chmod -R ug+rwx storage bootstrap/cache 2>/dev/null || true

php artisan storage:link --force

echo "==> Storage ready (public/storage -> storage/app/public)"
