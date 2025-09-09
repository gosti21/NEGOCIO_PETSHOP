# Etapa Node (build de Vite)
FROM node:20 AS node-build
WORKDIR /var/www
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Etapa PHP
FROM php:8.2-fpm
WORKDIR /var/www

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev zip nginx \
    && docker-php-ext-install pdo_mysql bcmath gd zip

# Instalar Composer directamente en PHP
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Copiar assets de Node
COPY --from=node-build /var/www/public/build public/build

# Copiar proyecto Laravel
COPY . .

# Ejecutar Composer + permisos Laravel
RUN composer install --no-dev --optimize-autoloader \
    && chown -R www-data:www-data storage bootstrap/cache

# Configuración Nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Copiar script de deploy
COPY deploy.sh /deploy.sh
RUN chmod +x /deploy.sh

# Exponer puerto
EXPOSE 80

# Comando de inicio
CMD ["sh", "/deploy.sh"]
