
FROM php:8.2-apache

# Cài mấy cái thư viện cần thiết để PHP nói chuyện với SQLite
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Bật mod_rewrite (để ông dùng .htaccess redirect các kiểu cho đẹp trai)
RUN a2enmod rewrite

# Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Copy toàn bộ code từ máy ông vào trong container
COPY . /var/www/html/

# 🔥 QUAN TRỌNG VÃI CHƯỞNG: Fix quyền ghi file SQLite
# Apache chạy dưới user 'www-data', nên phải gán quyền cho nó
# để nó có thể ghi/sửa cái file database.sqlite của ông.
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Mở port 80 (Apache mặc định chạy port này)
EXPOSE 80
CMD ["sh", "-c", "mkdir -p /var/www/html/db && chown -R www-data:www-data /var/www/html/db && chmod -R 775 /var/www/html/db && apache2-foreground"]