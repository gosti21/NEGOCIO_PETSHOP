#!/bin/sh
set -e

export COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependencias Laravel
composer install --no-dev --optimize-autoloader

# Publicar Livewire assets
php artisan livewire:publish --assets || true

# Crear link de storage
php artisan storage:link || true

# Limpiar cachés y compilar vistas
php artisan config:clear
php artisan view:clear
php artisan config:cache
php artisan view:cache

# Iniciar PHP-FPM en foreground escuchando TCP 9000
php-fpm -F -R -y /usr/local/etc/php-fpm.conf -d listen=127.0.0.1:9000 &

# Iniciar Nginx en foreground
nginx -g "daemon off;"
