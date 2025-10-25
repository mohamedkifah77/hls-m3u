# استخدم صورة PHP مع Apache جاهزة
FROM php:8.2-apache

# انسخ جميع ملفات المشروع داخل المجلد الافتراضي لـ Apache
COPY . /var/www/html/

# فعّل mod_rewrite (ليس ضروري جداً ولكن مفيد للمستقبل)
RUN a2enmod rewrite

# المنفذ الافتراضي لـ Render هو 10000، نخبر Apache باستخدامه
EXPOSE 10000

# شغّل Apache
CMD ["apache2-foreground"]
