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
    git \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    nginx \
    && docker-php-ext-install pdo_mysql bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer directamente en PHP
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Copiar assets de Node
COPY --from=node-build /var/www/public/build public/build

# Copiar proyecto Laravel
COPY . .

# Instalar dependencias PHP y asignar permisos
RUN composer install --no-dev --optimize-autoloader \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Configuración Nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Copiar script de deploy
COPY deploy.sh /deploy.sh
RUN chmod +x /deploy.sh

# Exponer puerto
EXPOSE 80

# Comando de inicio
CMD ["sh", "/deploy.sh"]
