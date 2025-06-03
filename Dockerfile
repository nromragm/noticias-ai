FROM php:8.2-fpm

# Instala dependencias del sistema y extensiones PHP necesarias para Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Instala Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copia tu proyecto al contenedor
COPY . .

# Instala dependencias de Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Exponer el puerto por defecto de PHP-FPM
EXPOSE 9000