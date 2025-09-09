FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    nginx \
    libpq-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo_mysql zip bcmath

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar proyecto
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Permisos de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copiar configuración de Nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Copiar script de inicio
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Exponer un puerto (Railway lo reemplaza, pero usa 8080 por convención)
EXPOSE 8080

# Iniciar con script
CMD ["sh", "/start.sh"]
