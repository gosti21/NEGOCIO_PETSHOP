#!/bin/sh
set -e

export COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependencias PHP
composer install --no-dev --optimize-autoloader

# Migraciones
php artisan migrate --force

# Publicar assets de Livewire
php artisan livewire:publish --assets

# NPM / Vite
npm install
npm run build

# Storage
php artisan storage:link || true

# Limpiar cachés anteriores (opcional, evita errores)
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generar cachés de Laravel
php artisan config:cache
# Solo ejecutar route:cache si estás seguro que no hay rutas duplicadas
php artisan route:cache || echo "⚠️ Error route:cache ignorado, revisar rutas duplicadas"
php artisan view:cache
