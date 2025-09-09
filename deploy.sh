#!/bin/sh
set -e

export COMPOSER_ALLOW_SUPERUSER=1

composer install --no-dev --optimize-autoloader

php artisan migrate --force || echo "⚠️ DB no disponible aún, migraciones omitidas"
php artisan storage:link || true
php artisan config:cache
php artisan view:cache

# Iniciar PHP-FPM y Nginx en foreground
php-fpm -F
nginx -g "daemon off;"
