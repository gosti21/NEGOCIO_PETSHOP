#!/bin/sh
set -e

export COMPOSER_ALLOW_SUPERUSER=1

# Composer
composer install --no-dev --optimize-autoloader

# Migraciones
php artisan migrate --force

# Livewire
php artisan livewire:publish --assets

# NPM / Build (si Node.js está instalado)
npm install
npm run build

# Storage
php artisan storage:link || true
