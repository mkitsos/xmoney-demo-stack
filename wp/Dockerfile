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

# Copy initialization scripts
COPY init-wordpress.sh /var/www/html/
COPY install-woocommerce.sh /var/www/html/
COPY add-sample-products.php /var/www/html/
RUN chmod +x /var/www/html/init-wordpress.sh \
  && chmod +x /var/www/html/install-woocommerce.sh

# Copy xMoney WooCommerce plugin
COPY plugins/xmoney-woocommerce /var/www/html/wp-content/plugins/xmoney-woocommerce
