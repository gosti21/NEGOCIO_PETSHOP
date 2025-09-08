#!/bin/sh
set -e

# Permitir plugins de Composer al correr como root en Railway
export COMPOSER_ALLOW_SUPERUSER=1

echo "🚀 Iniciando deploy en Railway..."

# =============================
# 1. Instalar dependencias
# =============================
echo "📦 Instalando dependencias de PHP..."
composer install --no-dev --optimize-autoloader

echo "📦 Instalando dependencias de Node..."
npm install

echo "⚡ Compilando assets con Vite..."
npm run build

# =============================
# 2. Migraciones y storage
# =============================
echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

echo "🔗 Creando symlink de storage..."
php artisan storage:link || true

# =============================
# 3. Limpiar y generar cachés
# =============================
echo "🧹 Limpiando cachés..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "⚡ Generando cachés optimizadas..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# =============================
# 4. Iniciar servidor PHP
# =============================
echo "🚀 Levantando servidor en puerto $PORT..."
php -S 0.0.0.0:$PORT -t public
    