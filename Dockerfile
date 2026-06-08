FROM php:8.4-apache

# ------------------------
# SYSTEM DEPENDENCIES
# ------------------------

RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    nodejs npm \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring exif pcntl bcmath gd zip \
    && docker-php-ext-enable pdo_mysql \
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

WORKDIR /var/www/html

# ------------------------
# APP COPY
# ------------------------
COPY . .

# ------------------------
# INSTALL DEPENDENCIES
# ------------------------
RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN npm install
RUN npm run build

# ------------------------
# STORAGE FIX (BUILD TIME SAFETY)
# ------------------------
RUN mkdir -p storage/logs bootstrap/cache

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# ------------------------
# CLEAN
# ------------------------
RUN rm -rf bootstrap/cache/*.php

# ------------------------
# START SCRIPT
# ------------------------
RUN printf '#!/bin/bash\n\
set -e\n\
\n\
echo "Runtime storage fix..."\n\
\n\
mkdir -p /var/www/html/storage/logs\n\
touch /var/www/html/storage/logs/laravel.log || true\n\
\n\
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true\n\
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true\n\
\n\
php artisan migrate --force || true\n\
php artisan config:cache || true\n\
php artisan route:cache || true\n\
php artisan view:cache || true\n\
php artisan storage:link || true\n\
\n\
exec apache2-foreground\n' > /usr/local/bin/start.sh

RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]