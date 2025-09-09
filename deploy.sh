#!/bin/sh
set -e

export COMPOSER_ALLOW_SUPERUSER=1

echo "Instalando dependencias Laravel..."
composer install --no-dev --optimize-autoloader

echo "Publicando assets Livewire..."
php artisan livewire:publish --assets || true

echo "Creando link de storage..."
php artisan storage:link || true

echo "Limpiando cachés..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Cacheando config y vistas..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Iniciando PHP-FPM..."
php-fpm -F &

echo "Iniciando Nginx..."
nginx -g "daemon off;"
