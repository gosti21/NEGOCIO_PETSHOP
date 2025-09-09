FROM php:8.2-fpm

# Instalar dependencias necesarias y extensiones PHP
RUN apt-get update && apt-get install -y \
    nginx git unzip libzip-dev libpng-dev libonig-dev libxml2-dev curl netcat-openbsd \
    && docker-php-ext-install pdo_mysql zip gd mbstring exif pcntl bcmath \
    && rm -rf /var/lib/apt/lists/*


# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar el proyecto completo
COPY . .

# Copiar configuración de Nginx y script de despliegue
COPY nginx.conf /etc/nginx/sites-available/default
COPY deploy.sh /usr/local/bin/deploy.sh

# Dar permisos al script y carpetas necesarias
RUN chmod +x /usr/local/bin/deploy.sh \
    && chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Exponer puerto 8080 (Railway lo usa)
EXPOSE 8080

# Cambiar Nginx para que escuche en 8080
RUN sed -i 's/listen 80;/listen 8080;/g' /etc/nginx/sites-available/default

# Comando para iniciar el contenedor
CMD ["deploy.sh"]
