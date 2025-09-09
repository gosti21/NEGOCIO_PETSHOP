#!/bin/sh
set -e

export COMPOSER_ALLOW_SUPERUSER=1

echo "✅ Instalando dependencias PHP..."
composer install --no-dev --optimize-autoloader || true

echo "🔗 Creando enlace de storage..."
php artisan storage:link || true

echo "⚡ Limpiando cachés..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "⚡ Cacheando configuración y vistas..."
php artisan config:cache
php artisan view:cache

echo "✅ Pre-deploy terminado. ⚠️ Migraciones deben correr solo después de que DB esté lista."
