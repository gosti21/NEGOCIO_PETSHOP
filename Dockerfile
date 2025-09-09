# 1. Base PHP 8.2 con extensiones necesarias
FROM php:8.2-cli

# 2. Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    npm \
    && docker-php-ext-install pdo_mysql zip gd mbstring exif pcntl bcmath \
    && rm -rf /var/lib/apt/lists/*

# 3. Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 4. Crear directorio de la app
WORKDIR /var/www

# 5. Copiar archivos de Composer y dependencias
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# 6. Copiar el resto del proyecto
COPY . .

# 7. Permisos para storage y bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

# 8. Exponer el puerto que Laravel usará
EXPOSE 8000

# 9. Comando para iniciar Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
