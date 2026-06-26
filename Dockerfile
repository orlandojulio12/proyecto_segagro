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

# Fix PSR-4: Windows no distingue mayúsculas/minúsculas en filenames
RUN [ -f app/Models/Complaint/pqr.php ] && mv app/Models/Complaint/pqr.php app/Models/Complaint/Pqr.php || true

RUN composer dump-autoload --optimize --no-scripts

# =========================
# Stage final de producción
# =========================
FROM php-base

WORKDIR /var/www

# Evitar warning "dubious ownership"
RUN git config --global --add safe.directory /var/www

# Copiar proyecto y vendor compilado
COPY . .
COPY --from=composer-builder /var/www/vendor ./vendor

# Fix PSR-4: renombrar pqr.php → Pqr.php (Windows → Linux no distingue case)
RUN [ -f app/Models/Complaint/pqr.php ] && mv app/Models/Complaint/pqr.php app/Models/Complaint/Pqr.php || true

# Configurar permisos
RUN mkdir -p storage/framework/{sessions,views,cache} storage/app/public/pqrs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Crear .env mínimo válido para comandos artisan durante el build.
# El .env real con credenciales lo inyecta Dokploy en runtime; entrypoint.sh hace config:cache.
RUN cp .env .env.prod \
    && printf 'APP_NAME=SEGAGRO\nAPP_ENV=production\nAPP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=\nAPP_DEBUG=false\nAPP_URL=http://localhost\nDB_CONNECTION=mysql\n' > .env

# Instalar Octane sin disparar post-autoload-dump (que lee .env), luego publicar configs
RUN composer require laravel/octane:^2.1 --with-all-dependencies --no-scripts \
    && composer dump-autoload --optimize --no-scripts \
    && php artisan octane:install --server=swoole \
    && php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --force \
    && php artisan vendor:publish --provider="OwenIt\Auditing\AuditingServiceProvider" --force \
    && php artisan package:discover --ansi

# Restaurar .env de producción
RUN mv .env.prod .env

# Puerto que expondrá Octane
EXPOSE 8000

# Copiar script de entrada
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Usar script como entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Ejecutar Octane + Swoole
CMD ["php", "artisan", "octane:start", "--server=swoole", "--host=0.0.0.0", "--port=8000", "--workers=auto"]
