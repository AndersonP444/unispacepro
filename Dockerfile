FROM php:8.3-apache

# Instalar dependencias del sistema y extensiones PHP necesarias para Laravel y MySQL (SSL)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    libonig-dev \
    libxml2-dev \
    ca-certificates \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql mbstring xml bcmath gd zip \
    && a2enmod rewrite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de PHP para producción
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Configurar DocumentRoot de Apache hacia /var/www/html/public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Permisos para Laravel y carpetas de imagenes
RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache public/images/perfiles public/images/cursos public/images/salones \
    && chown -R www-data:www-data storage bootstrap/cache public/images \
    && chmod -R 775 storage bootstrap/cache public/images \
    && chmod +x /var/www/html/docker/start.sh

EXPOSE 10000

CMD ["/var/www/html/docker/start.sh"]

