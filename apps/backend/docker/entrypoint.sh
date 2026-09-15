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

if [ -d storage-oauth ]; then
  if [ -f storage-oauth/oauth-private.key ]; then
    cp storage-oauth/oauth-private.key storage/oauth-private.key
  fi

  if [ -f storage-oauth/oauth-public.key ]; then
    cp storage-oauth/oauth-public.key storage/oauth-public.key
  fi
fi

if [ ! -f storage/oauth-private.key ] || [ ! -f storage/oauth-public.key ]; then
  php artisan passport:keys --force || true
fi

if [ -d storage-oauth ]; then
  cp storage/oauth-private.key storage-oauth/oauth-private.key 2>/dev/null || true
  cp storage/oauth-public.key storage-oauth/oauth-public.key 2>/dev/null || true
fi

php artisan optimize:clear || true
php artisan config:cache || true

exec "$@"
