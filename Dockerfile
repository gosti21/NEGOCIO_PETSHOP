FROM php:8.2-fpm

# Instalar dependencias del sistema y extensiones PHP
RUN apt-get update && apt-get install -y \
    nginx git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip gd mbstring exif pcntl bcmath

WORKDIR /var/www

# Copiar todo el proyecto
COPY . .

# Copiar archivo de configuración de Nginx (ahora en la raíz)
COPY ./default.conf.template /etc/nginx/conf.d/default.conf.template

EXPOSE 80

# Iniciar PHP-FPM y Nginx
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
