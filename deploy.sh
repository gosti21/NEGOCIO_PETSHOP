#!/bin/sh
set -e

# Permitir plugins de Composer al correr como root en Railway
export COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependencias optimizadas (sin dev)
composer install --no-dev --optimize-autoloader

# Ejecutar migraciones forzadas
php artisan migrate --force

npm install
npm run build


# Enlace de storage (ignora error si ya existe)
php artisan storage:link || true


# Mantener el servidor en ejecución en el puerto que asigna Railway
php -S 0.0.0.0:$PORT -t public
