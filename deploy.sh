#!/bin/sh
set -e

export COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependencias PHP
composer install --no-dev --optimize-autoloader

# Migraciones (opcional en producción)
php artisan migrate --force || echo "⚠️ DB no disponible aún, migraciones omitidas"

# Livewire assets (ya copiados si estaban en node-build)
php artisan livewire:publish --assets || echo "⚠️ Livewire assets ya publicados"

# Storage link
php artisan storage:link || true

# Limpiar cachés antiguos
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generar cachés Laravel
php artisan config:cache
# php artisan route:cache  # solo si no hay rutas duplicadas
php artisan view:cache

# Iniciar servicios
php-fpm -F -R      # F = foreground, R = permitir TCP para Nginx
nginx -g "daemon off;"
