FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip

# Install PHP extensions (IMPORTANT PART)
RUN docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pgsql

# Enable Apache rewrite
RUN a2enmod rewrite

# Copy project
COPY . /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80