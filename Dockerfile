FROM php:8.2-apache

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar proyecto
COPY . .

# Instalar dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    zip unzip git libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Configurar permisos de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configuración de Apache para Laravel
RUN echo "<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>" > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# Ejecutar migraciones y arrancar Apache
CMD php artisan migrate --force && apache2-foreground
