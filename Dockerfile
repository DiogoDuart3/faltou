# syntax=docker/dockerfile:1
# composer:2 floats to the newest PHP (8.6 broke installs); build deps on the runtime PHP line.
FROM serversideup/php:8.4-cli@sha256:5110afeb3bf2e6fc1f91247f52ed2c9df3df5bcd20c65645f2c3290ad4a92c6c AS vendor
USER root
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

FROM serversideup/php:8.4-fpm-nginx@sha256:a9152816e2a652b75bd68c3ed3621d2b6188d82d4d7ecae93660a3057136ba24
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
