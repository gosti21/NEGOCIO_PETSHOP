FROM php:8.2-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    zip unzip git libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Habilitar mod_rewrite en Apache
RUN a2enmod rewrite

# Configuración de Apache para Laravel
RUN echo "<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# Copiar proyecto
WORKDIR /var/www/html
COPY . .

# Instalar Composer (si no lo tienes ya en vendor)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Exponer puerto
EXPOSE 80

CMD ["apache2-foreground"]
