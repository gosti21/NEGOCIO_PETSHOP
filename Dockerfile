FROM php:8.2-fpm

# Instalar dependencias del sistema y PHP
RUN apt-get update && apt-get install -y \
    nginx git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip gd mbstring exif pcntl bcmath \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www

# Copiar solo composer.json y composer.lock primero
COPY composer.json composer.lock ./

# Instalar dependencias
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Copiar el resto del proyecto
COPY . .

# Copiar configuración de Nginx
COPY default.conf /etc/nginx/conf.d/default.conf

# Dar permisos al deploy
RUN chmod +x deploy.sh

EXPOSE 80

CMD ["sh", "-c", "sh deploy.sh"]
