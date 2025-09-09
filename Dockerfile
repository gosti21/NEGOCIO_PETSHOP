# Base PHP-FPM
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    npm \
    nginx \
    curl \
    && docker-php-ext-install pdo_mysql zip gd mbstring exif pcntl bcmath \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Crear directorio de trabajo
WORKDIR /var/www

# Copiar composer.json y composer.lock primero
COPY composer.json composer.lock ./

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader || true

# Copiar todo el proyecto
COPY . .

# Permisos
RUN chmod +x deploy.sh

# Copiar configuración de Nginx
COPY default.conf /etc/nginx/conf.d/default.conf

# Exponer puerto
EXPOSE 80
