FROM composer:latest AS composer

FROM php:8.2-fpm

COPY --from=composer /usr/bin/composer /usr/bin/composer

LABEL org.opencontainers.image.description="The php-mbstring-extension is a PHP library that provides support for multibyte strings that are not covered by the standard PHP string functions."

WORKDIR /app
COPY . .

RUN apt-get update \
    && apt-get -y upgrade \
    && apt-get -y install zip \
    && composer update
