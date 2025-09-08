#!/bin/sh
set -e

# Instalar dependencias
composer install --no-dev --optimize-autoloader

# Migraciones
php artisan migrate --force

# Enlace de storage
php artisan storage:link || true

# Cachés para producción (opcional, recomendado)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Mantener el servidor en ejecución
php artisan serve --host=0.0.0.0 --port=$PORT
