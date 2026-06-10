# استخدام نسخة PHP الرسمية المدمجة مع سيرفر Apache
FROM php:8.2-apache

# تثبيت وتفعيل إضافات mysqli و pdo_mysql للاتصال بقاعدة البيانات بنجاح
RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable mysqli pdo_mysql

# تفعيل مود الـ Rewrite الخاص بـ Apache للمشاريع الاحترافية
RUN a2enmod rewrite

# تحديد مسار العمل الافتراضي داخل الحاوية
WORKDIR /var/www/html/