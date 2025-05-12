FROM php:8.1-fpm


# System dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    zip \
    libpq-dev \
    wkhtmltopdf \
    postgresql \
    postgresql-client \
    libpng-dev 

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql zip

# composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# project root (code)  – permissions don’t matter: read‑only in compose
WORKDIR /var/www
COPY . .

# Laravel permissions
RUN chown -R www-data:www-data /var/www
RUN chown -R www-data:www-data /etc/passwd
 


RUN composer install --no-interaction --prefer-dist
EXPOSE 9000
CMD ["php-fpm"]
