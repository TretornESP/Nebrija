FROM php:7.4-apache

# Basic dependencies
RUN apt-get update && apt-get install -y \
    libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

COPY index.php /var/www/html/
RUN chown -R www-data:www-data /var/www/html
