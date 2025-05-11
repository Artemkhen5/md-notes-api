FROM php:8.3-fpm-alpine

WORKDIR /var/www

RUN apk update && apk add \
    build-base \
    postgresql-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl

RUN docker-php-ext-install pdo pdo_pgsql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S www -G www

USER www

COPY --chown=www:www . /var/www

RUN composer install --no-interaction --no-scripts

EXPOSE 9000
