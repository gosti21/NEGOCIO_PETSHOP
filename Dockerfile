# Base PHP-FPM
FROM php:8.2-fpm

# Instalar dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql zip gd mbstring exif pcntl bcmath

# Directorio de trabajo
WORKDIR /var/www

# Copiar proyecto completo
COPY . .

# Copiar plantilla de Nginx
COPY ./nginx/default.conf.template /etc/nginx/conf.d/default.conf.template

# Copiar entrypoint
COPY ./entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Exponer puerto (Railway normalmente redirige a 80)
EXPOSE 80

# Usar entrypoint
ENTRYPOINT ["/entrypoint.sh"]
