#!/bin/bash
# Script to install and activate WooCommerce
# This runs after WordPress is installed

set -e

echo "Installing WooCommerce..."

# Wait for WordPress to be ready
until wp core is-installed --allow-root 2>/dev/null; do
    echo "Waiting for WordPress to be installed..."
    sleep 2
done

# Install WooCommerce plugin
wp plugin install woocommerce --activate --allow-root

# Activate xMoney WooCommerce plugin
if wp plugin is-installed xmoney-woocommerce --allow-root 2>/dev/null; then
    wp plugin activate xmoney-woocommerce --allow-root
    echo "xMoney WooCommerce plugin activated!"
fi

echo "WooCommerce installed and activated successfully!"

