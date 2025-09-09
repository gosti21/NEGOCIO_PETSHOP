#!/bin/sh
set -e

export COMPOSER_ALLOW_SUPERUSER=1

# Esperar a que la base de datos esté lista
MAX_TRIES=15
i=0
until php artisan migrate:status > /dev/null 2>&1 || [ $i -eq $MAX_TRIES ]; do
    i=$((i+1))
    echo "Esperando base de datos... intento $i/$MAX_TRIES"
    sleep 5
done

# Composer + Livewire
composer install --no-dev --optimize-autoloader
php artisan livewire:publish --assets || true
php artisan storage:link || true

# Cachés Laravel
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan view:cache

# Iniciar PHP-FPM y Nginx
php-fpm -D
nginx -g "daemon off;"
