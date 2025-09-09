#!/bin/sh
set -e

export COMPOSER_ALLOW_SUPERUSER=1

echo "📦 Instalando dependencias PHP..."
composer install --no-dev --optimize-autoloader

echo "🔧 Ejecutando migraciones..."
php artisan migrate --force || echo "⚠️ DB no disponible aún, migraciones omitidas"

echo "🔗 Creando link de storage..."
php artisan storage:link || true

echo "🗂️ Cacheando configuración y vistas..."
php artisan config:cache
php artisan view:cache

# Opcional: build de frontend si existe package.json
if [ -f package.json ]; then
    echo "📦 Instalando dependencias Node..."
    npm install
    echo "🏗️ Construyendo frontend..."
    npm run build
fi

echo "🚀 Iniciando PHP-FPM y Nginx..."
php-fpm -D
nginx -g "daemon off;"
