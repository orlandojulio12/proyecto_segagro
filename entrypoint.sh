#!/bin/bash
set -e

# Crear enlace simbólico de storage si no existe
if [ ! -L public/storage ]; then
    php artisan storage:link
fi

# Ejecutar Octane (o cualquier comando pasado al contenedor)
exec "$@"
