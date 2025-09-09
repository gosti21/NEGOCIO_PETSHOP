# Etapa final (PHP)
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    unzip git curl libpng-dev libonig-dev libxml2-dev zip nginx \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copiar aplicación
WORKDIR /var/www
COPY . .

# Copiar assets de Vite construidos (si los tienes)
# COPY --from=node-build /var/www/public/build public/build

# Deploy script
COPY deploy.sh /deploy.sh
RUN chmod +x /deploy.sh && /deploy.sh

# Permisos Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Configuración Nginx
COPY ./nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["sh", "-c", "php-fpm && nginx -g 'daemon off;'"]
