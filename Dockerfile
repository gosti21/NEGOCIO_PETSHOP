# Etapa Node (build de Vite)
FROM node:20 AS node-build
WORKDIR /var/www
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build  # Construye assets de Vite

# Etapa PHP
FROM php:8.2-fpm
WORKDIR /var/www

# Instalar dependencias del sistema + extensiones PHP
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev zip nginx \
    && docker-php-ext-install pdo_mysql bcmath gd zip mbstring exif pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Copiar assets de Node
COPY --from=node-build /var/www/public/build public/build

# Copiar Laravel
COPY . .

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && php artisan storage:link || true \
    && php artisan config:cache \
    && php artisan view:cache

# Configurar Nginx
COPY default.conf /etc/nginx/conf.d/default.conf

# Exponer puerto
EXPOSE 80

# Start Command para Railway
CMD ["sh", "-c", "php-fpm -F & nginx -g 'daemon off;'"]
