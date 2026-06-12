FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git

# IMPORTANT: PostgreSQL + PDO driver
RUN docker-php-ext-install pdo pdo_pgsql pgsql mysqli pdo_mysql

# Enable Apache rewrite
RUN a2enmod rewrite

# Copy project
COPY . /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80