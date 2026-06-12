FROM php:8.2-apache

# 1. تثبيت الحزم الأساسية لنظام التشغيل
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. تثبيت وتفعيل دريفرات PostgreSQL لـ PHP
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# 3. إجبار تفعيل الامتدادات داخل إعدادات PHP (php.ini) لحل مشكلة could not find driver
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini \
    && echo "extension=pdo_pgsql" >> /usr/local/etc/php/php.ini \
    && echo "extension=pgsql" >> /usr/local/etc/php/php.ini

# 4. تفعيل الـ Rewrite الخاص بسيرفر Apache
RUN a2enmod rewrite

# 5. نسخ ملفات المشروع بالكامل وتحديد الصلاحيات
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80