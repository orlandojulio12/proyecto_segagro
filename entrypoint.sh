#!/bin/bash

# Crear enlace simbólico de storage si no existe
if [ ! -L public/storage ]; then
    php artisan storage:link || true
fi

# Ejecutar migraciones pendientes (crea tablas sessions, cache, jobs si faltan)
php artisan migrate --force || true

# Regenerar caché con las variables de entorno inyectadas por Dokploy
php artisan config:clear || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Iniciar el scheduler de Laravel en segundo plano (se ejecuta cada 60 s)
(while true; do php /var/www/artisan schedule:run >> /dev/null 2>&1; sleep 60; done) &

# Iniciar el worker de colas en segundo plano
php /var/www/artisan queue:work --sleep=3 --tries=3 --timeout=90 >> /dev/null 2>&1 &

# Ejecutar Octane (o cualquier comando pasado al contenedor)
exec "$@"
