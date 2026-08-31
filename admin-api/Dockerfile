FROM php:8.3-fpm-alpine

# Install system dependencies & build packages
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    oniguruma-dev \
    mysql-client \
    icu-dev \
    bash

# Install required PHP extensions for Laravel & MySQL
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd intl

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory inside container
WORKDIR /var/www/html

# Copy application files into container
COPY . .

# Set default file permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
CMD ["php-fpm"]