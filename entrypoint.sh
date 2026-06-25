#!/bin/bash
set -e

# Crear enlace simbólico de storage si no existe
if [ ! -L public/storage ]; then
    php artisan storage:link
fi

# Regenerar caché con las variables de entorno inyectadas por Dokploy
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar el scheduler de Laravel en segundo plano (se ejecuta cada 60 s)
(while true; do php /var/www/artisan schedule:run >> /dev/null 2>&1; sleep 60; done) &

# Iniciar el worker de colas en segundo plano
php /var/www/artisan queue:work --sleep=3 --tries=3 --timeout=90 >> /dev/null 2>&1 &

# Ejecutar Octane (o cualquier comando pasado al contenedor)
exec "$@"
