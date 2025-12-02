# =========================
# Base PHP con extensiones necesarias
# =========================
FROM php:8.2-fpm-bullseye AS php-base

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libzip-dev \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd mbstring pcntl posix \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && pecl install swoole \
    && docker-php-ext-enable swoole \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www

# =========================
# Install Composer + Dependencies
# =========================
FROM php-base AS composer-builder

COPY composer.json composer.lock ./

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependencias Laravel (sin dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copiar código del proyecto
COPY . .

RUN composer dump-autoload --optimize

# =========================
# Stage final de producción
# =========================
FROM php-base

WORKDIR /var/www

# Evitar warning "dubious ownership"
RUN git config --global --add safe.directory /var/www

# Copiar proyecto, vendor y caches
COPY . .
COPY --from=composer-builder /var/www/vendor ./vendor

# Configurar permisos
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Instalar Composer (para comandos futuros tipo artisan)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar Laravel Octane + Swoole
RUN composer require laravel/octane:^2.1 --with-all-dependencies \
    && php artisan octane:install --server=swoole

# Cache de producción
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Puerto que expondrá Octane
EXPOSE 8000

# Copiar script de entrada
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Usar script como entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Ejecutar Octane + Swoole
CMD ["php", "artisan", "octane:start", "--server=swoole", "--host=0.0.0.0", "--port=8000", "--workers=auto"]
