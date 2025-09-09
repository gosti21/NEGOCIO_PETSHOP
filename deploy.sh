#!/bin/sh
set -e

export COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependencias PHP
composer install --no-dev --optimize-autoloader

# Migraciones (opcional: puedes ejecutarlas manualmente en producción)
php artisan migrate --force || true

# Storage
php artisan storage:link || true

# Limpiar cachés anteriores
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generar cachés de Laravel
php artisan config:cache
# php artisan route:cache  # solo si no hay rutas duplicadas
php artisan view:cache
