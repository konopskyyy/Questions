FROM php:8.4-fpm

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y \
    unzip libicu-dev libpq-dev libzip-dev zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install intl pdo pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR "/application"

RUN mkdir -p /var/www/html/var/cache /var/www/html/var/log \
    && chown -R www-data:www-data /var/www/html/var \
    && chmod -R 775 /var/www/html/var

RUN echo "zend_extension=xdebug.so" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

USER www-data

CMD ["php-fpm"]
