FROM prestashop/prestashop:8.1-apache

RUN a2enmod rewrite headers expires
COPY php.ini /usr/local/etc/php/conf.d/custom.ini
