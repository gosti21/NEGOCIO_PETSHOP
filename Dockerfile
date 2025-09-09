# Etapa base: PHP
FROM php:8.2-fpm

# Instalar dependencias del sistema y extensiones PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    npm \
    nginx \
    && docker-php-ext-install pdo_mysql zip gd mbstring exif pcntl bcmath \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Crear directorio de trabajo
WORKDIR /var/www

# Copiar archivos de Laravel
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

COPY . .

# Configurar permisos de storage y bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Copiar configuración de Nginx
COPY default.conf /etc/nginx/conf.d/default.conf

# Exponer el puerto que Railway asigna
EXPOSE 8080

# Comando de inicio
CMD php-fpm && nginx -g "daemon off;"
