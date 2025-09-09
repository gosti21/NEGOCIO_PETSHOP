# Base PHP-FPM
FROM php:8.2-fpm

# Instalar dependencias de sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    nginx git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip gd mbstring exif pcntl bcmath

# Directorio de trabajo dentro del contenedor
WORKDIR /var/www

# Copiar todo el proyecto al contenedor
COPY . .

# Mover el archivo de configuración de Nginx a la ubicación correcta
RUN mv /var/www/default.conf.template /etc/nginx/conf.d/default.conf.template

# Exponer puerto
EXPOSE 80

# Iniciar PHP-FPM y Nginx
CMD ["sh", "-c", "sh deploy.sh"]
