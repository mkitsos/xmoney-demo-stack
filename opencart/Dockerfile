FROM php:8.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    unzip curl libpng-dev libjpeg-dev libfreetype6-dev libzip-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install mysqli gd zip \
  && a2enmod rewrite headers expires \
  && rm -rf /var/lib/apt/lists/*

# Download OpenCart (pin a specific version)
ARG OPENCART_VERSION=4.0.2.3
RUN curl -L -o /tmp/opencart.zip https://github.com/opencart/opencart/releases/download/${OPENCART_VERSION}/opencart-${OPENCART_VERSION}.zip \
  && unzip /tmp/opencart.zip -d /tmp/oc \
  && rm -f /tmp/opencart.zip \
  && rm -rf /var/www/html/* \
  && cp -R /tmp/oc/opencart-${OPENCART_VERSION}/upload/* /var/www/html/ \
  && rm -rf /tmp/oc

# Make installer writable
RUN chown -R www-data:www-data /var/www/html \
  && chmod -R 775 /var/www/html

COPY php.ini /usr/local/etc/php/conf.d/custom.ini
