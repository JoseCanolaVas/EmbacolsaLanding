#!/usr/bin/env sh
set -e

cd /var/www/html

mkdir -p \
  storage/logs \
  storage/framework/cache \
  storage/framework/sessions \
  storage/framework/views \
  storage/app/public \
  bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

php artisan storage:link || true

if [ ! -f storage/oauth-private.key ] || [ ! -f storage/oauth-public.key ]; then
  php artisan passport:keys --force || true
fi

php artisan optimize:clear || true
php artisan config:cache || true

exec "$@"
