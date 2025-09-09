#!/bin/sh
set -e

export COMPOSER_ALLOW_SUPERUSER=1

# Composer
composer install --no-dev --optimize-autoloader

# Publicar Livewire assets
php artisan livewire:publish --assets || true

# Storage
php artisan storage:link || true

# Cachés Laravel
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache || echo "⚠️ Rutas duplicadas, revisar"
php artisan view:cache

# Iniciar PHP-FPM + Nginx
php-fpm8.2 -F &
nginx -g "daemon off;"
