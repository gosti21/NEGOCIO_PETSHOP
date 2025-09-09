# Etapa base PHP
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

# Copiar código
WORKDIR /var/www
COPY . .

# Copiar plantilla de nginx
COPY ./nginx/default.conf.template /etc/nginx/conf.d/default.conf.template

# Copiar entrypoint
COPY ./entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Exponer puerto (Railway asigna dinámicamente)
EXPOSE 80

# Ejecutar entrypoint
ENTRYPOINT ["/entrypoint.sh"]
