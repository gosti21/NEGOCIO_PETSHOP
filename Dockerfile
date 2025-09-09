FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    npm nginx curl \
    && docker-php-ext-install pdo_mysql zip gd mbstring exif pcntl bcmath \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY . .

COPY default.conf /etc/nginx/conf.d/default.conf
COPY deploy.sh /var/www/deploy.sh
RUN chmod +x /var/www/deploy.sh

EXPOSE 80

# Ejecutar deploy.sh al iniciar
CMD ["/var/www/deploy.sh"]
