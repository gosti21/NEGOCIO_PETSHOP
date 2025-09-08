#!/bin/sh

# Reemplaza el placeholder en nginx.conf por el valor real del PORT de Railway
sed -i "s/\$PORT/${PORT}/g" /etc/nginx/conf.d/default.conf

# Migraciones en producción (opcional)
php artisan migrate --force

# Arrancar PHP-FPM en background
php-fpm -D

# Arrancar Nginx en foreground (mantiene el contenedor vivo)
nginx -g 'daemon off;'
