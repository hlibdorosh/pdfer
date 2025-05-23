FROM php:8.2-apache

# Ghostscript + zip
RUN apt-get update && apt-get install -y \
    ghostscript \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копіюємо твій код
COPY .. /var/www/html/

# Права на temp
RUN mkdir -p /var/www/html/temp && chmod -R 777 /var/www/html/temp

# Вмикаємо mod_rewrite
RUN a2enmod rewrite
