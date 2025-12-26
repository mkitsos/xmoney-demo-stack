FROM wordpress:6.9-php8.2-apache

RUN a2enmod rewrite headers expires

RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    curl \
  && docker-php-ext-install zip \
  && curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
  && chmod +x wp-cli.phar \
  && mv wp-cli.phar /usr/local/bin/wp \
  && rm -rf /var/lib/apt/lists/*

COPY php.ini /usr/local/etc/php/conf.d/custom.ini
