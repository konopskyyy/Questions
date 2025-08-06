FROM php:8.4-fpm

ENV COMPOSER_ALLOW_SUPERUSER=1

# hadolint ignore=DL3008
RUN apt-get update && apt-get install -y \
    unzip libicu-dev libpq-dev libzip-dev zip \
    && docker-php-ext-install intl pdo pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR "/application"

RUN mkdir -p /var/www/html/var/cache /var/www/html/var/log \
    && chown -R www-data:www-data /var/www/html/var \
    && chmod -R 775 /var/www/html/var

USER www-data

CMD ["php-fpm"]
