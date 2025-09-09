# Imagen base PHP-FPM
FROM php:8.2-fpm

# Instalar dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    npm nginx curl \
    && docker-php-ext-install pdo_mysql zip gd mbstring exif pcntl bcmath \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar archivos de Composer y ejecutar instalación
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copiar todo el proyecto
COPY . .

# Crear enlace de storage si no existe
RUN php artisan storage:link || true

# Limpiar y cachear Laravel
RUN php artisan config:clear \
    && php artisan cache:clear \
    && php artisan route:clear \
    && php artisan view:clear \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Copiar configuración de Nginx
COPY default.conf /etc/nginx/conf.d/default.conf

# Exponer puerto HTTP
EXPOSE 80

# Arranque de PHP-FPM y Nginx
CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]
