#!/bin/sh

# Reemplazar placeholder $PORT en nginx.conf con el valor real
sed -i "s/\$PORT/${PORT}/g" /etc/nginx/conf.d/default.conf

# Iniciar PHP-FPM en background
php-fpm -D

# Iniciar Nginx en foreground
nginx -g "daemon off;"
