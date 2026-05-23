#!/bin/sh
set -eu

echo "==> Laravel migrate"
php artisan migrate --force

echo "==> Idempotent seed (DeploySeeder)"
php artisan db:seed --class=DeploySeeder --force

echo "==> Storage"
/bin/sh ./scripts/ensure-storage.sh

echo "==> Pre-deploy finished"
