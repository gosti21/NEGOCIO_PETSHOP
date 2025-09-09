#!/bin/sh
set -e

export COMPOSER_ALLOW_SUPERUSER=1

# Composer
composer install --no-dev --optimize-autoloader

# Migraciones opcionales
php artisan migrate --force || echo "⚠️ DB no disponible aún, migraciones omitidas"

# Livewire
php artisan livewire:publish --assets || echo "⚠️ Livewire assets ya publicados"

# Storage
php artisan storage:link || true

# Limpiar cachés
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generar cachés
php artisan config:cache
php artisan view:cache
# php artisan route:cache  # solo si no hay rutas duplicadas
