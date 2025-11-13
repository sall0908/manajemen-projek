# Gunakan image PHP dengan ekstensi Laravel lengkap
FROM php:8.2-fpm

# Instal dependensi sistem yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Set working directory di container
WORKDIR /var/www/html

# Copy composer dari image resmi
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy semua file Laravel ke dalam container
COPY . .

# Install dependensi Laravel
RUN composer install --no-dev --optimize-autoloader

# Generate key (biar aplikasi siap dipakai)
RUN php artisan key:generate

# Pastikan storage & bootstrap/cache bisa ditulis
RUN chmod -R 777 storage bootstrap/cache

# Jalankan PHP built-in server untuk Laravel
CMD php artisan serve --host=0.0.0.0 --port=8080

# Port default yang akan dipakai Railway
EXPOSE 8080
