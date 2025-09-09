#!/bin/sh
set -e

export COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependencias PHP
composer install --no-dev --optimize-autoloader

# Migraciones
php artisan migrate --force

# Livewire
php artisan livewire:publish --assets

# NPM / Vite
npm install
npm run build

# Storage
php artisan storage:link || true

# Cachés de Laravel (opcional, para producción)
php artisan config:cache
# php artisan route:cache  # Comentado temporalmente
php artisan view:cache
php artisan route:cache
