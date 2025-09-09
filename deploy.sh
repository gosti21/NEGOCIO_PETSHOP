#!/bin/sh
set -e

export COMPOSER_ALLOW_SUPERUSER=1

echo "Instalando dependencias PHP..."
composer install --no-dev --optimize-autoloader || true

echo "Migraciones..."
php artisan migrate --force || echo "⚠️ DB no disponible aún"

echo "Storage link..."
php artisan storage:link || true

echo "Cacheando..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

if [ -f package.json ]; then
    npm install
    npm run build
fi

# Arrancar PHP-FPM y Nginx
php-fpm & nginx -g "daemon off;"

