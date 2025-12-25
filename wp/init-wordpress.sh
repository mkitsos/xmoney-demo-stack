#!/bin/bash
# Script to initialize WordPress, install WooCommerce, and add sample products
# This runs after WordPress container starts

echo "Starting WordPress initialization..."

# Check if WP-CLI is available
if ! command -v wp &> /dev/null; then
    echo "Error: WP-CLI is not available. Make sure the container was rebuilt with WP-CLI installed."
    exit 1
fi

# Check if WordPress is already installed
if wp core is-installed --allow-root 2>/dev/null; then
    echo "✓ WordPress is installed."
    
    # Check if WooCommerce is installed
    if wp plugin is-installed woocommerce --allow-root 2>/dev/null; then
        echo "✓ WooCommerce is already installed."
        
        # Activate WooCommerce if not active
        if ! wp plugin is-active woocommerce --allow-root 2>/dev/null; then
            echo "Activating WooCommerce..."
            wp plugin activate woocommerce --allow-root
        else
            echo "✓ WooCommerce is already active."
        fi
        
        # Add sample products if WooCommerce is active
        if wp plugin is-active woocommerce --allow-root 2>/dev/null; then
            echo "Adding sample products..."
            wp eval-file /var/www/html/add-sample-products.php --allow-root || echo "Note: Products may already exist or WooCommerce needs initialization."
        fi
    else
        echo "Installing WooCommerce..."
        wp plugin install woocommerce --activate --allow-root
        
        echo "Waiting for WooCommerce to initialize..."
        sleep 5  # Give WooCommerce time to initialize
        
        echo "Adding sample products..."
        wp eval-file /var/www/html/add-sample-products.php --allow-root || echo "Note: Products creation may need retry after WooCommerce setup."
    fi
else
    echo "⚠ WordPress is not installed yet."
    echo ""
    echo "Please complete the WordPress setup wizard first:"
    echo "1. Open http://localhost:8080 in your browser"
    echo "2. Complete the WordPress installation wizard"
    echo "3. Then run this script again:"
    echo "   docker exec -it xmoney-demo-stack-wp-1 /var/www/html/init-wordpress.sh"
    exit 1
fi

echo ""
echo "✓ Initialization complete!"

