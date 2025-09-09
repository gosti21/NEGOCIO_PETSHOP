#!/bin/sh
set -e

# 🔹 Permitir plugins de Composer al correr como root en Railway
export COMPOSER_ALLOW_SUPERUSER=1

# 🔹 Instalar dependencias optimizadas (sin dev)
composer install --no-dev --optimize-autoloader

# 🔹 Ejecutar migraciones forzadas
php artisan migrate --force

# 🔹 Publicar assets de Livewire
php artisan livewire:publish --assets

# 🔹 Enlace de storage (ignora error si ya existe)
php artisan storage:link || true

# 🔹 Limpiar y generar cachés optimizadas para producción
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# 🔹 Mantener el servidor en ejecución en el puerto asignado por Railway
php -S 0.0.0.0:$PORT -t public
