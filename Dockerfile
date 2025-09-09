# Etapa de build (solo Node)
FROM node:20 AS node-build
WORKDIR /var/www
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build  # construye Vite assets

# Etapa final (PHP)
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    unzip git curl libpng-dev libonig-dev libxml2-dev zip nginx \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copiar aplicación y assets de Node
WORKDIR /var/www
COPY --from=node-build /var/www/public/build public/build
COPY --from=node-build /var/www/package*.json ./
COPY . .

# Ejecutar composer y Livewire
RUN composer install --no-dev --optimize-autoloader \
    && php artisan livewire:publish --assets \
    && php artisan storage:link || true \
    && php artisan config:cache \
    && php artisan view:cache \
    && php artisan route:cache || echo "⚠️ Rutas duplicadas, revisar"

# Permisos Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Configuración Nginx
COPY ./nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
