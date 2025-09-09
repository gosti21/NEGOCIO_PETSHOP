# Etapa build para Node (Vite)
FROM node:20 AS node-build
WORKDIR /var/www
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Etapa final PHP
FROM php:8.2-fpm

# Dependencias del sistema + extensiones PHP
RUN apt-get update && apt-get install -y \
    unzip git curl libpng-dev libonig-dev libxml2-dev zip nginx \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Crear directorio de trabajo
WORKDIR /var/www
COPY --from=node-build /var/www/public/build public/build
COPY . .

# Deploy (instalación, Livewire, caches)
COPY deploy.sh /deploy.sh
RUN chmod +x /deploy.sh

# Nginx config
COPY ./nginx.conf /etc/nginx/conf.d/default.conf

# Permisos Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 80
CMD ["/deploy.sh"]
