#!/bin/sh
set -e

export COMPOSER_ALLOW_SUPERUSER=1

echo "Instalando dependencias PHP..."
composer install --no-dev --optimize-autoloader || true

echo "Ejecutando migraciones..."
php artisan migrate --force || echo "⚠️ DB no disponible, migraciones omitidas"

echo "Creando link de storage..."
php artisan storage:link || true

echo "Limpiando y cacheando..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Construyendo frontend si existe..."
if [ -f package.json ]; then
    npm install
    npm run build
fi
