#!/usr/bin/env sh
set -e

if [ ! -f .env ]; then
    printenv | awk -F= '
        /^[A-Za-z_][A-Za-z0-9_]*=/ {
            key=$1
            value=substr($0, length(key) + 2)
            if (key ~ /^(APP_|LOG_|DB_|BROADCAST_|CACHE_|FILESYSTEM_|QUEUE_|SESSION_|MEMCACHED_|REDIS_|MAIL_|AWS_|PUSHER_|VITE_)/) {
                printf "%s=%s\n", key, value
            }
        }
    ' > .env
fi

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force --no-interaction
fi

php artisan storage:link --force || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

exec "$@"
