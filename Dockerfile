FROM php:8.2-apache

WORKDIR /var/www/html

COPY public/ /var/www/html/

RUN a2enmod rewrite 