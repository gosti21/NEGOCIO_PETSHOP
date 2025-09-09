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

echo "🚀 Iniciando PHP-FPM..."
php-fpm -F &

# Esperar a que PHP-FPM esté listo
timeout=30
while ! nc -z 127.0.0.1 9000; do
  sleep 1
  timeout=$((timeout-1))
  if [ $timeout -le 0 ]; then
    echo "❌ PHP-FPM no arrancó a tiempo"
    exit 1
  fi
done

echo "🚀 PHP-FPM listo, arrancando Nginx..."
nginx -g 'daemon off;'
