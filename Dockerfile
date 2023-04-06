# Use the official PHP 8.2.4 image as the base image
FROM php:8.2.4-fpm-alpine as php

# Install dependencies
RUN apk add --no-cache \
    curl \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip \
    && rm -rf /var/cache/apk/*

ARG MAIL_HOST
ARG MAIL_PORT
ARG MAIL_USERNAME
ARG MAIL_PASSWORD


RUN apk update && apk upgrade --no-cache

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy composer.json and composer.lock
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --prefer-dist --no-dev --no-scripts --no-progress --no-suggest

# Copy the rest of the application code
COPY . .

# Set file permissions for Laravel
RUN chown -R www-data:www-data storage bootstrap/cache
RUN echo "upload_max_filesize = 16M" >> /usr/local/etc/php/php.ini
RUN echo "post_max_size = 16M" >> /usr/local/etc/php/php.ini

# Expose port 9000
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]

