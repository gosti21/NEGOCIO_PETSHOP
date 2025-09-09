#!/bin/sh
set -e

export COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependencias PHP
composer install --no-dev --optimize-autoloader

# Migraciones y link de storage
php artisan migrate --force || echo "⚠️ DB no disponible aún, migraciones omitidas"
php artisan storage:link || true

# Cachear configuración y rutas
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Opcional: frontend
if [ -f package.json ]; then
    npm install
    npm run build
fi

php-fpm -D

# Arranca Nginx en primer plano
nginx -g "daemon off;"
