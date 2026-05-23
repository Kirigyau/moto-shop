#!/bin/sh
set -eu

echo "==> Laravel migrate"
php artisan migrate --force

echo "==> Idempotent seed (DeploySeeder)"
php artisan db:seed --class=DeploySeeder --force

echo "==> Storage link"
php artisan storage:link 2>/dev/null || true

echo "==> Pre-deploy finished"
