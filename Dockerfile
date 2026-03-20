
FROM php:8.2-apache


RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

RUN a2enmod rewrite


WORKDIR /var/www/html


COPY . /var/www/html/


RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html


EXPOSE 80
CMD ["sh", "-c", "mkdir -p /var/www/html/db && chown -R www-data:www-data /var/www/html/db && chmod -R 775 /var/www/html/db && apache2-foreground"]