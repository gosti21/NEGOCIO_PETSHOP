#!/bin/sh
set -e

# Permitir plugins de Composer al correr como root en Railway
export COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependencias optimizadas (sin dev)
composer install --no-dev --optimize-autoloader

# Ejecutar migraciones forzadas
php artisan migrate --force

# Enlace de storage (ignora error si ya existe)
php artisan storage:link || true

# Generar cachés para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache



# Limpiar cachés previas
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Generar cachés optimizadas
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Mantener el servidor en ejecución en el puerto que asigna Railway
php -S 0.0.0.0:$PORT -t public

