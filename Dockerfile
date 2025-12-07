FROM php:8.2-fpm-alpine

# Install system packages
RUN apk add --no-cache \
    mysql-client \
    git \
    && docker-php-ext-install pdo pdo_mysql

# Working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy code
COPY . /var/www/html

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose PHP-FPM port
EXPOSE 9000

CMD ["php-fpm"]
