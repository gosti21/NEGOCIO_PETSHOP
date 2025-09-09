#!/bin/sh
set -e

export COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependencias PHP
composer install --no-dev --optimize-autoloader

# Migraciones (opcional en producción)
php artisan migrate --force || echo "⚠️ DB no disponible aún, migraciones omitidas"

# Livewire assets
php artisan livewire:publish --assets || echo "⚠️ Livewire assets ya publicados"

# Storage
php artisan storage:link || true

# Limpiar cachés
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generar cachés Laravel
php artisan config:cache
# php artisan route:cache  # Descomentar solo si no hay rutas duplicadas
php artisan view:cache

# Iniciar servicios
php-fpm -D
nginx -g "daemon off;"
