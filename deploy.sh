#!/bin/sh
set -e

# Permitir Composer como root
export COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependencias sin dev
composer install --no-dev --optimize-autoloader

# Migrar base de datos
php artisan migrate --force

# Storage link
php artisan storage:link || true

# Generar cachés optimizadas
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 🚨 Usar PHP-FPM o Laravel Sail en producción
# Para Railway, puedes usar:
php artisan serve --host=0.0.0.0 --port=$PORT
