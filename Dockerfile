# 1. استخدام النسخة الرسمية المستقرة لـ PHP مع خادم Apache بالتوافق مع Docker Compose
FROM php:8.2-apache

# 2. تحديث حزم النظام وتثبيت الإضافات الأساسية لـ MySQLi والـ PDO
RUN apt-get update && apt-get upgrade -y \
    && docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli

# 3. تفعيل موديل Rewrite الخاص بـ Apache (مفيد جداً لإدارة الروابط وتحسين الحماية)
RUN a2enmod rewrite

# 4. إعادة تشغيل سيرفر Apache لتطبيق التعديلات الجديدة داخل بيئة الحاوية
RUN service apache2 restart