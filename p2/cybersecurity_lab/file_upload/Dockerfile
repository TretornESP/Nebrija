FROM php:7.4-apache
COPY src/ /var/www/html/
RUN mkdir -p /var/www/html/uploads
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 777 /var/www/html/uploads
