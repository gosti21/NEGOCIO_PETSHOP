#!/bin/sh
set -e

export COMPOSER_ALLOW_SUPERUSER=1

echo "✅ Instalando dependencias Composer..."
composer install --no-dev --optimize-autoloader || true

echo "🔗 Creando enlace storage..."
php artisan storage:link || true

echo "⚡ Cacheando configuración y vistas..."
php artisan config:cache
php artisan view:cache

echo "🚀 Iniciando PHP-FPM y Nginx..."

# Arranca PHP-FPM en background
php-fpm -D

# Arranca Nginx en primer plano
nginx -g 'daemon off;'
