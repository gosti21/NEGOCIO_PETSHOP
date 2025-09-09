# Etapa de build (Node/Vite)
FROM node:20 AS node-build
WORKDIR /var/www
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Etapa final (PHP)
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git unzip nginx libzip-dev zip libpng-dev libonig-dev \
    && docker-php-ext-install pdo_mysql zip bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar proyecto y assets de Node
COPY --from=node-build /var/www/public/build public/build
COPY --from=node-build /var/www/package*.json ./
COPY . .

# Deploy script
COPY deploy.sh /deploy.sh
RUN chmod +x /deploy.sh

# Permisos Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Configuración Nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Exponer puerto
EXPOSE 80

# Iniciar PHP-FPM y Nginx
CMD ["sh", "/deploy.sh"]
