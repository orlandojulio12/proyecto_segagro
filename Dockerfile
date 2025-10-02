# Etapa 1: frontend
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci  # incluye devDependencies
COPY . .
RUN npm run build  # genera los assets

# Etapa 2: PHP-FPM
FROM php:8.2-fpm-alpine
RUN apk add --no-cache git curl libpng-dev libxml2-dev libzip-dev zip unzip mysql-client oniguruma-dev freetype-dev libjpeg-turbo-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

# Composer deps
COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction --prefer-dist

# Copiar código y assets compilados
COPY . .
COPY --from=frontend /app/public /var/www/html/public

RUN mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage bootstrap/cache

RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && composer dump-autoload --optimize

CMD ["php-fpm"]
EXPOSE 9000