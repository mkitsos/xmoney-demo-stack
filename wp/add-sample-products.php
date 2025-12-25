<?php
/**
 * Add sample products to WooCommerce
 * 
 * Usage: wp eval-file add-sample-products.php --allow-root
 */

// Load WordPress
require_once('/var/www/html/wp-load.php');

// Check if WooCommerce is active
if (!class_exists('WooCommerce')) {
    echo "Error: WooCommerce plugin is not active.\n";
    exit(1);
}

// Sample products data
$sample_products = [
    [
        'name' => 'Premium Wireless Headphones',
        'type' => 'simple',
        'regular_price' => '99.99',
        'description' => 'High-quality wireless headphones with noise cancellation and 30-hour battery life. Perfect for music lovers and professionals.',
        'short_description' => 'Premium wireless headphones with noise cancellation',
        'sku' => 'WH-001',
        'stock_quantity' => 50,
        'manage_stock' => true,
        'categories' => ['Electronics', 'Audio'],
        'tags' => ['wireless', 'headphones', 'audio', 'premium'],
    ],
    [
        'name' => 'Organic Cotton T-Shirt',
        'type' => 'variable',
        'description' => 'Comfortable organic cotton t-shirt available in multiple sizes and colors. Made from 100% organic cotton.',
        'short_description' => 'Comfortable organic cotton t-shirt',
        'sku' => 'TS-001',
        'attributes' => [
            [
                'name' => 'Size',
                'options' => ['S', 'M', 'L', 'XL'],
                'variation' => true,
            ],
            [
                'name' => 'Color',
                'options' => ['Black', 'White', 'Blue', 'Red'],
                'variation' => true,
            ],
        ],
        'variations' => [
            ['regular_price' => '24.99', 'attributes' => ['Size' => 'S', 'Color' => 'Black'], 'sku' => 'TS-001-S-BLK'],
            ['regular_price' => '24.99', 'attributes' => ['Size' => 'M', 'Color' => 'Black'], 'sku' => 'TS-001-M-BLK'],
            ['regular_price' => '24.99', 'attributes' => ['Size' => 'L', 'Color' => 'White'], 'sku' => 'TS-001-L-WHT'],
            ['regular_price' => '24.99', 'attributes' => ['Size' => 'XL', 'Color' => 'Blue'], 'sku' => 'TS-001-XL-BLU'],
        ],
        'categories' => ['Clothing', 'T-Shirts'],
        'tags' => ['clothing', 't-shirt', 'organic', 'cotton'],
    ],
    [
        'name' => 'Stainless Steel Water Bottle',
        'type' => 'simple',
        'regular_price' => '19.99',
        'sale_price' => '14.99',
        'description' => 'Durable stainless steel water bottle, perfect for daily use. Keeps drinks cold for 24 hours or hot for 12 hours. BPA-free.',
        'short_description' => 'Durable stainless steel water bottle',
        'sku' => 'WB-001',
        'stock_quantity' => 100,
        'manage_stock' => true,
        'categories' => ['Accessories', 'Drinkware'],
        'tags' => ['water bottle', 'stainless steel', 'eco-friendly'],
    ],
    [
        'name' => 'Smart Fitness Watch',
        'type' => 'simple',
        'regular_price' => '249.99',
        'description' => 'Advanced fitness tracking watch with heart rate monitor, GPS, and 7-day battery life. Water-resistant and compatible with iOS and Android.',
        'short_description' => 'Advanced fitness tracking watch',
        'sku' => 'FW-001',
        'stock_quantity' => 25,
        'manage_stock' => true,
        'categories' => ['Electronics', 'Wearables'],
        'tags' => ['fitness', 'watch', 'smartwatch', 'health'],
    ],
    [
        'name' => 'Artisan Coffee Beans (1lb)',
        'type' => 'simple',
        'regular_price' => '18.99',
        'description' => 'Premium single-origin coffee beans, freshly roasted. Notes of chocolate and caramel. Perfect for espresso and pour-over.',
        'short_description' => 'Premium single-origin coffee beans',
        'sku' => 'CB-001',
        'stock_quantity' => 200,
        'manage_stock' => true,
        'categories' => ['Food & Beverage', 'Coffee'],
        'tags' => ['coffee', 'beans', 'artisan', 'organic'],
    ],
    [
        'name' => 'Leather Laptop Bag',
        'type' => 'simple',
        'regular_price' => '149.99',
        'description' => 'Elegant genuine leather laptop bag with padded compartment for laptops up to 15 inches. Multiple pockets for organization.',
        'short_description' => 'Elegant genuine leather laptop bag',
        'sku' => 'LB-001',
        'stock_quantity' => 30,
        'manage_stock' => true,
        'categories' => ['Accessories', 'Bags'],
        'tags' => ['laptop bag', 'leather', 'professional'],
    ],
];

$created_count = 0;
$error_count = 0;

foreach ($sample_products as $product_data) {
    try {
        // Check if product already exists by SKU
        if (!empty($product_data['sku'])) {
            $existing = wc_get_product_id_by_sku($product_data['sku']);
            if ($existing) {
                echo "Product with SKU '{$product_data['sku']}' already exists. Skipping...\n";
                continue;
            }
        }

        // Create product
        $product = new WC_Product_Simple();
        
        if ($product_data['type'] === 'variable') {
            $product = new WC_Product_Variable();
        }

        $product->set_name($product_data['name']);
        $product->set_description($product_data['description']);
        $product->set_short_description($product_data['short_description']);
        $product->set_regular_price($product_data['regular_price']);
        
        if (!empty($product_data['sale_price'])) {
            $product->set_sale_price($product_data['sale_price']);
        }

        if (!empty($product_data['sku'])) {
            $product->set_sku($product_data['sku']);
        }

        if (isset($product_data['manage_stock']) && $product_data['manage_stock']) {
            $product->set_manage_stock(true);
            $product->set_stock_quantity($product_data['stock_quantity']);
            $product->set_stock_status('instock');
        }

        // Set categories
        if (!empty($product_data['categories'])) {
            $term_ids = [];
            foreach ($product_data['categories'] as $cat_name) {
                $term = get_term_by('name', $cat_name, 'product_cat');
                if (!$term) {
                    $term = wp_insert_term($cat_name, 'product_cat');
                    if (!is_wp_error($term)) {
                        $term_ids[] = $term['term_id'];
                    }
                } else {
                    $term_ids[] = $term->term_id;
                }
            }
            $product->set_category_ids($term_ids);
        }

        // Set tags
        if (!empty($product_data['tags'])) {
            $product->set_tag_ids([]);
            foreach ($product_data['tags'] as $tag_name) {
                $term = get_term_by('name', $tag_name, 'product_tag');
                if (!$term) {
                    $term = wp_insert_term($tag_name, 'product_tag');
                    if (!is_wp_error($term)) {
                        $product->set_tag_ids(array_merge($product->get_tag_ids(), [$term['term_id']]));
                    }
                } else {
                    $product->set_tag_ids(array_merge($product->get_tag_ids(), [$term->term_id]));
                }
            }
        }

        // Handle variable product attributes and variations
        if ($product_data['type'] === 'variable' && !empty($product_data['attributes'])) {
            $attributes = [];
            foreach ($product_data['attributes'] as $attr_data) {
                $attribute = new WC_Product_Attribute();
                $attribute->set_id(0);
                $attribute->set_name($attr_data['name']);
                $attribute->set_options($attr_data['options']);
                $attribute->set_visible(true);
                $attribute->set_variation($attr_data['variation']);
                $attributes[] = $attribute;
            }
            $product->set_attributes($attributes);
        }

        $product_id = $product->save();

        if ($product_id) {
            echo "✓ Created product: {$product_data['name']} (ID: $product_id)\n";
            $created_count++;

            // Create variations for variable products
            if ($product_data['type'] === 'variable' && !empty($product_data['variations'])) {
                foreach ($product_data['variations'] as $variation_data) {
                    $variation = new WC_Product_Variation();
                    $variation->set_parent_id($product_id);
                    $variation->set_regular_price($variation_data['regular_price']);
                    
                    if (!empty($variation_data['sku'])) {
                        $variation->set_sku($variation_data['sku']);
                    }

                    // Set variation attributes
                    $variation_attributes = [];
                    foreach ($variation_data['attributes'] as $attr_name => $attr_value) {
                        $variation_attributes['attribute_' . strtolower(str_replace(' ', '-', $attr_name))] = $attr_value;
                    }
                    $variation->set_attributes($variation_attributes);
                    $variation->set_manage_stock(true);
                    $variation->set_stock_quantity(10);
                    $variation->set_stock_status('instock');

                    $variation_id = $variation->save();
                    if ($variation_id) {
                        echo "  → Created variation: " . implode(' / ', $variation_data['attributes']) . "\n";
                    }
                }
            }
        } else {
            echo "✗ Failed to create product: {$product_data['name']}\n";
            $error_count++;
        }
    } catch (Exception $e) {
        echo "✗ Error creating product '{$product_data['name']}': " . $e->getMessage() . "\n";
        $error_count++;
    }
}

echo "\n";
echo "Summary:\n";
echo "  Created: $created_count products\n";
echo "  Errors: $error_count\n";
echo "\nDone! Visit your WooCommerce store to see the sample products.\n";

