#!/bin/sh

# Reemplazar placeholder del puerto
sed -i "s/PORT_PLACEHOLDER/${PORT}/g" /etc/nginx/conf.d/default.conf

# Migraciones (opcional, solo si quieres correrlas al iniciar)
php artisan migrate --force

# Iniciar PHP-FPM en background
php-fpm -D

# Iniciar Nginx en foreground
nginx -g "daemon off;"
