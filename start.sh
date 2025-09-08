#!/bin/sh

# Reemplazar placeholder del puerto en nginx.conf
sed -i "s/PORT_PLACEHOLDER/${PORT}/g" /etc/nginx/conf.d/default.conf

# Esperar a que MySQL esté disponible
echo "Esperando a que la DB esté lista..."
while ! mysqladmin ping -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
    sleep 2
done

echo "DB lista, ejecutando migraciones..."
php artisan migrate --force

# Iniciar PHP-FPM en background
php-fpm -D

# Iniciar Nginx en foreground
nginx -g "daemon off;"
