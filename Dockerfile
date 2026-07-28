# syntax=docker/dockerfile:1
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction
COPY . ./
RUN composer dump-autoload --optimize --no-interaction

FROM node:22-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci --no-audit --no-fund
COPY . ./
RUN npm run build

FROM serversideup/php:8.4-fpm-nginx
ENV APP_BASE_DIR=/var/www/html \
    AUTORUN_ENABLED=false \
    PHP_OPCACHE_ENABLE=1
USER root
WORKDIR /var/www/html
COPY . ./
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build
RUN mkdir -p storage/app storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache
USER www-data
EXPOSE 80
