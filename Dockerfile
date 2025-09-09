FROM php:8.2-fpm

# Instalar dependencias del sistema y extensiones PHP
RUN apt-get update && apt-get install -y \
    nginx git unzip libzip-dev libpng-dev libonig-dev libxml2-dev nodejs npm \
    && docker-php-ext-install pdo_mysql zip gd mbstring exif pcntl bcmath \
    && rm -rf /var/lib/apt/lists/*

# Directorio de trabajo
WORKDIR /var/www

# Copiar solo composer.json y composer.lock primero (capa de cache)
COPY composer.json composer.lock ./

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# NO instalar dependencias aún, se hará en deploy.sh

# Copiar el resto del proyecto
COPY . .

# Copiar configuración de Nginx
COPY default.conf /etc/nginx/conf.d/default.conf

# Dar permisos al deploy
RUN chmod +x deploy.sh

# Exponer puerto
EXPOSE 80

# Ejecutar deploy.sh al iniciar el contenedor
CMD ["sh", "deploy.sh"]
