
FROM php:8.2-apache


RUN apt-get update && apt-get upgrade -y \
    && docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli


RUN a2enmod rewrite


RUN service apache2 restart