FROM php:8.2-apache

# 1. تثبيت الحزم الأساسية للنظام
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. تثبيت وتفعيل الدريفرات لـ PHP بشكل مؤكد وإجباري
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && docker-php-ext-enable pdo_pgsql pgsql

# 3. تفعيل الـ Rewrite الخاص بـ Apache
RUN a2enmod rewrite

# 4. نسخ الملفات وتحديد الصلاحيات
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80