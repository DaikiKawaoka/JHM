FROM php:7.4.1-fpm

# php.iniをコピー
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

# Composer install
COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

#node.js install
COPY --from=node:12.14 /usr/local/bin /usr/local/bin
COPY --from=node:12.14 /usr/local/lib /usr/local/lib

RUN apt-get update \
    && apt-get install -y \
    git \
    zip \
    unzip \
    vim \
    zlib1g-dev \
    libzip-dev \
    libfreetype6-dev \
    && docker-php-ext-install pdo_mysql bcmath

RUN docker-php-ext-install zip
RUN docker-php-ext-configure gd --with-freetype
RUN docker-php-ext-install -j$(nproc) gd

WORKDIR /var/www/html