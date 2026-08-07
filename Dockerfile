FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

FROM composer:2.7 AS backend
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --ignore-platform-reqs --prefer-dist --no-scripts
COPY . .
RUN composer dump-autoload --optimize

FROM php:8.3-cli-alpine

# Instalamos las librerías para SQLite y MySQL como root
RUN apk add --no-cache sqlite-dev mysql-dev \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql

RUN addgroup -S becasgroup && adduser -S becasuser -G becasgroup

WORKDIR /var/www/html
COPY --from=backend /app /var/www/html
COPY --from=frontend /app/public/build /var/www/html/public/build

RUN mkdir -p database \
    && chown -R becasuser:becasgroup /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/database

# MUY IMPORTANTE: La aplicación corre con el usuario seguro que pide el profe
USER becasuser

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]