#!/usr/bin/env bash
set -e

PORT="${PORT:-10000}"

# Ajustar puerto de Apache al asignado por Render
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Limpiar y optimizar caches con las variables de entorno reales de Render
php artisan config:clear
php artisan view:clear
php artisan route:clear

exec apache2-foreground

