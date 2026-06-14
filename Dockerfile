FROM php:8.3-cli

# تثبيت الحزم المطلوبة للنظام
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev

# تثبيت Node.js (الإصدار 20)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# تنظيف ذاكرة التخزين المؤقت
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# تثبيت امتدادات PHP المطلوبة
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# الحصول على أحدث إصدار من Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# إعداد مجلد العمل
WORKDIR /var/www

# نسخ ملفات المشروع
COPY . /var/www/

# كشف المنافذ المطلوبة
EXPOSE 8000 5173

# السماح بتنفيذ الأوامر (تتغير حسب الحاجة)
CMD ["bash"]
