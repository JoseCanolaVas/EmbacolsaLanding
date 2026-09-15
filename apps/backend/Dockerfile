FROM php:8.2-fpm-alpine AS php-extensions

WORKDIR /var/www/html

RUN apk upgrade --no-cache \
    && apk add --no-cache \
        icu-libs \
        libjpeg-turbo \
        libpng \
        libpq \
        libzip \
        oniguruma \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        libzip-dev \
        oniguruma-dev \
        postgresql-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install \
        bcmath \
        exif \
        ftp \
        gd \
        intl \
        mbstring \
        pcntl \
        pdo_mysql \
        pdo_pgsql \
        pgsql \
        zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps


# ================================
# Composer / dependencias producción
# ================================
FROM php-extensions AS production-dependencies

WORKDIR /var/www/html

RUN apk add --no-cache git unzip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY apps/backend/composer.json apps/backend/composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts


# ================================
# Producción
# ================================
FROM php-extensions AS production

WORKDIR /var/www/html

RUN apk add --no-cache bash

COPY --from=production-dependencies /var/www/html/vendor ./vendor
COPY apps/backend ./
COPY apps/backend/docker/entrypoint.sh /usr/local/bin/embacolsa-entrypoint

RUN mkdir -p \
        storage/logs \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/app/public \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x /usr/local/bin/embacolsa-entrypoint

EXPOSE 9000

ENTRYPOINT ["embacolsa-entrypoint"]
CMD ["php-fpm"]
