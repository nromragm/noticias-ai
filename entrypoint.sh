#!/bin/bash

# Da permisos a los directorios necesarios
chmod -R 777 storage bootstrap/cache

# Genera la clave de la app si no existe
if [ ! -f .env ]; then
    cp .env.example .env
fi
php artisan key:generate --force

# Ejecuta migraciones (opcional, quítalo si no quieres migrar siempre)
php artisan migrate --force

# Limpia cachés
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Ejecuta el comando original del contenedor (php-fpm)
exec php-fpm