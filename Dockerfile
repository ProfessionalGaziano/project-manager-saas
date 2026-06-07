FROM php:8.4-apache

# ------------------------
# 1. DEPENDENCIES SISTEMA
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
        zip

# ------------------------
# 2. APACHE CONFIG
# ------------------------
RUN a2enmod rewrite

# DocumentRoot su Laravel /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf

RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# ServerName fix (evita warning)
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# ------------------------
# 3. COMPOSER
# ------------------------
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ------------------------
# 4. COPY PROGETTO
# ------------------------
COPY . .

# ------------------------
# 5. PHP DEPENDENCIES
# ------------------------
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ------------------------
# 6. FRONTEND BUILD (OPZIONALE)
# ------------------------
RUN npm install && npm run build

# ------------------------
# 7. PERMISSIONS
# ------------------------
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

RUN chmod -R 775 storage bootstrap/cache
RUN rm -rf bootstrap/cache/*.php

# ------------------------
# 8. ENTRYPOINT PULITO
# ------------------------
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

# ------------------------
# 9. PORT CONFIG (RENDER)
# ------------------------
ENV PORT=80
EXPOSE 80

CMD ["/start.sh"]