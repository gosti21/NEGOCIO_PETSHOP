FROM php:8.2-fpm

# Instalar dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    nginx git unzip libzip-dev libpng-dev libonig-dev libxml2-dev curl netcat \
    && docker-php-ext-install pdo_mysql zip gd mbstring exif pcntl bcmath \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Crear directorios necesarios
RUN mkdir -p /run/php /var/www

# Copiar archivos del proyecto
WORKDIR /var/www
COPY . .

# Copiar configuración personalizada de Nginx
COPY nginx.conf /etc/nginx/sites-available/default

# Copiar script de inicio
COPY deploy.sh /usr/local/bin/deploy.sh
RUN chmod +x /usr/local/bin/deploy.sh

# Permisos necesarios para Laravel
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Exponer el puerto esperado por Railway
EXPOSE 8080

# Cambiar puerto por defecto de Nginx a 8080
RUN sed -i 's/listen 80;/listen 8080;/' /etc/nginx/sites-available/default

# Comando de arranque
CMD ["deploy.sh"]
