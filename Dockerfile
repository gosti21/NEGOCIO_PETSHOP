# Etapa Node
FROM node:20 AS node-build
WORKDIR /var/www
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Etapa PHP
FROM php:8.2-fpm
WORKDIR /var/www

# Copiar assets de Node
COPY --from=node-build /var/www/public/build public/build

# Copiar proyecto Laravel
COPY . .

# Composer y permisos
RUN composer install --no-dev --optimize-autoloader \
    && chown -R www-data:www-data storage bootstrap/cache

# Config Nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Copiar deploy.sh
COPY deploy.sh /deploy.sh
RUN chmod +x /deploy.sh

EXPOSE 80
CMD ["sh", "/deploy.sh"]
