# Gunakan image PHP + Apache
FROM php:8.2-apache

# Aktifkan ekstensi PHP untuk MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Salin semua file project ke folder Apache
COPY . /var/www/html/

# Set direktori kerja
WORKDIR /var/www/html/

# (Opsional) Atur permission jika perlu
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Buka port 80
EXPOSE 80
