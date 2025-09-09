#!/bin/sh
set -e

export COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependencias de Composer
composer install --no-dev --optimize-autoloader

# Migraciones y cache de Laravel
php artisan migrate --force || echo "⚠️ DB no disponible aún, migraciones omitidas"
php artisan storage:link || true
php artisan config:cache
php artisan view:cache

# Iniciar PHP-FPM y Nginx
php-fpm -D
nginx -g "daemon off;"
