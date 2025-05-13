# Dockerfile para Laravel 12 + Vite + Node + Nginx
FROM php:8.2-fpm

# Instala dependencias del sistema
RUN apt-get update \
    && apt-get install -y \
        git \
        curl \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        zip \
        unzip \
        nginx \
        supervisor \
        libzip-dev \
        libpq-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Instala Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Instala Node.js (usado para Vite y frontend)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Crea directorio de la app
WORKDIR /var/www/html

# Copia el código fuente
COPY . .

# Instala dependencias de PHP y Node
RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && npm install \
    && npm run build

# Permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copia configuración de Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Copia configuración de Supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expone el puerto 80
EXPOSE 80

# Comando de arranque
CMD ["/usr/bin/supervisord"]
