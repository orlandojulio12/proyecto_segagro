
# Etapa 1: compilar assets
FROM node:20-alpine as frontend
WORKDIR /app

# Copiar solo package.json y lock para instalar dependencias
COPY package*.json ./

# Instalar dependencias del frontend
RUN npm ci

# Copiar el resto del proyecto
COPY . .

# Compilar assets (asumiendo que usás Vite o Laravel Mix)
RUN npm run build

# Etapa 2: PHP con Laravel
FROM php:8.2-fpm-alpine

# Instalar extensiones necesarias
RUN apk add --no-cache git curl libpng-dev libxml2-dev libzip-dev zip unzip mysql-client \
    oniguruma-dev freetype-dev libjpeg-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip opcache

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar los archivos del proyecto desde la etapa frontend (ya tiene los assets compilados)
COPY --from=frontend /app /var/www/html

# Instalar dependencias de PHP (Laravel)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Asignar permisos (ajustá según cómo manejes permisos en producción)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache