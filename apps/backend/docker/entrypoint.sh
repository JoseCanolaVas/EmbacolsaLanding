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

mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/oauth-keys bootstrap/cache

if [ -z "$APP_KEY" ]; then
    if [ -s storage/oauth-keys/app.key ]; then
        APP_KEY="$(cat storage/oauth-keys/app.key)"
    else
        APP_KEY="$(php -r 'echo "base64:".base64_encode(random_bytes(32));')"
        echo "$APP_KEY" > storage/oauth-keys/app.key
    fi

    export APP_KEY

    if grep -q '^APP_KEY=' .env; then
        sed -i "s|^APP_KEY=.*|APP_KEY=$APP_KEY|" .env
    else
        echo "APP_KEY=$APP_KEY" >> .env
    fi
fi

chown -R www-data:www-data storage bootstrap/cache || true
chmod -R ug+rw storage bootstrap/cache || true

php artisan config:clear || true

if [ ! -s storage/oauth-keys/oauth-private.key ] || [ ! -s storage/oauth-keys/oauth-public.key ]; then
    php artisan passport:keys --force --no-interaction
fi

chown www-data:www-data storage/oauth-keys/oauth-private.key storage/oauth-keys/oauth-public.key storage/oauth-keys/app.key 2>/dev/null || true

php artisan storage:link --force || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

exec "$@"
