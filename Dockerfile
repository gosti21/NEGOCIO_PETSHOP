# Base PHP-FPM
FROM php:8.2-fpm

# Instalar dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    nginx git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip gd mbstring exif pcntl bcmath \
    && rm -rf /var/lib/apt/lists/*

# Directorio de trabajo dentro del contenedor
WORKDIR /var/www

# Copiar todo el proyecto al contenedor
COPY . .

# Copiar archivo de configuración de Nginx
COPY default.conf /etc/nginx/conf.d/default.conf

# Dar permisos de ejecución al deploy.sh
RUN chmod +x deploy.sh

# Exponer puerto 80
EXPOSE 80

# Iniciar PHP-FPM y Nginx usando deploy.sh
CMD ["sh", "deploy.sh"]
