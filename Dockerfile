
# Etapa 1: compilar assets
FROM node:20-alpine as frontend
WORKDIR /app
COPY package*.json ./

# Instalar todas las dependencias (dev incluidas) para poder compilar
RUN npm ci

# Copiar el resto del código
COPY . .

# Compilar los assets
RUN npm run build

# Etapa 2: PHP-FPM con Laravel
FROM php:8.2-fpm-alpine

# Instalar extensiones necesarias y herramientas
RUN apk add --no-cache git curl libpng-dev libxml2-dev libzip-dev zip unzip mysql-client \
    oniguruma-dev freetype-dev libjpeg-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip opcache

# Copiar Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Instalar dependencias de PHP
COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction --prefer-dist

# Copiar todo el código de la app
COPY . .

# Copiar los assets compilados desde la etapa frontend
COPY --from=frontend /app/public /var/www/html/public

# Preparar directorios de Laravel
RUN mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage bootstrap/cache

# Cachear configuraciones y rutas de Laravel
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && composer dump-autoload --optimize

# Ejecutar PHP-FPM
CMD ["php-fpm"]

EXPOSE 9000