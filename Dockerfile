FROM php:8.4-apache

# ------------------------
# SYSTEM DEPENDENCIES
# ------------------------
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    nodejs \
    npm \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
    && apt-get clean

# ------------------------
# APACHE CONFIG
# ------------------------
RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf

RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# ------------------------
# COMPOSER
# ------------------------
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ------------------------
# APP
# ------------------------
WORKDIR /var/www/html

COPY . .

# ------------------------
# PHP DEPENDENCIES
# ------------------------
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

# ------------------------
# FRONTEND BUILD
# ------------------------
RUN npm install
RUN npm run build

# ------------------------
# CACHE CLEANUP
# ------------------------
RUN rm -rf bootstrap/cache/*.php

# ------------------------
# PERMISSIONS
# ------------------------
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# ------------------------
# START SCRIPT
# ------------------------
RUN printf '#!/bin/bash\n\
set -e\n\
echo "Starting Laravel..."\n\
php artisan optimize:clear || true\n\
php artisan storage:link || true\n\
exec apache2-foreground\n' > /usr/local/bin/start.sh

RUN chmod +x /usr/local/bin/start.sh

# ------------------------
# PORT
# ------------------------
EXPOSE 80

CMD ["/usr/local/bin/start.sh"]