# Etapa build de Node/Vite
FROM node:20 AS node-build
WORKDIR /var/www
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Etapa final PHP + Nginx
FROM php:8.2-fpm

# Instalar dependencias del sistema + extensiones PHP
RUN apt-get update && apt-get install -y \
    unzip git curl libpng-dev libonig-dev libxml2-dev zip nginx \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Crear directorio de trabajo
WORKDIR /var/www

# Copiar assets compilados de Node/Vite
COPY --from=node-build /var/www/public/build public/build

# Copiar todo el proyecto
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Configurar PHP-FPM para escuchar en TCP 9000
RUN sed -i 's|^listen = .*|listen = 127.0.0.1:9000|' /usr/local/etc/php-fpm.d/www.conf

# Copiar deploy.sh y darle permisos
COPY deploy.sh /deploy.sh
RUN chmod +x /deploy.sh

# Copiar configuración de Nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Permisos Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Exponer el puerto HTTP
EXPOSE 80

# Ejecutar PHP-FPM + Nginx
CMD ["/deploy.sh"]
