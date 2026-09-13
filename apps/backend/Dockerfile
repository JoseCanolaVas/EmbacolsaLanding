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

COPY apps/backend/api/composer.json apps/backend/api/composer.lock ./

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

COPY apps/backend/api ./

COPY --from=production-dependencies /var/www/html/vendor ./vendor

RUN mkdir -p \
        storage/logs \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]