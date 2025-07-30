FROM php:8.1-apache

# Copy mã web vào container
COPY . /var/www/html/

# Mở port 80
EXPOSE 80
