<?php
if (!defined('ABSPATH')) {
    exit;
}

class ProductFilterSetup {
    
    public static function activate() {
        self::create_sample_products();
        flush_rewrite_rules();
    }
    
    public static function deactivate() {
        flush_rewrite_rules();
    }
    
    private static function create_sample_products() {
        $existing_products = get_posts([
            'post_type' => 'product',
            'posts_per_page' => 1,
            'post_status' => 'publish'
        ]);
        
        if (!empty($existing_products)) {
            return;
        }
        
        $sample_products = [
            ['title' => 'Performance T-Shirt Men', 'price' => 250000, 'type' => 'Men', 'category' => 'T-Shirts'],
            ['title' => 'Athletic Shorts Women', 'price' => 180000, 'type' => 'Women', 'category' => 'Shorts'],
            ['title' => 'Unisex Running Shoes', 'price' => 750000, 'type' => 'Unisex', 'category' => 'Shoes'],
            ['title' => 'Men Training Jacket', 'price' => 450000, 'type' => 'Men', 'category' => 'Jackets'],
            ['title' => 'Women Yoga Pants', 'price' => 320000, 'type' => 'Women', 'category' => 'Pants'],
            ['title' => 'Unisex Sports Cap', 'price' => 120000, 'type' => 'Unisex', 'category' => 'Accessories']
        ];
        
        $categories = ['T-Shirts', 'Shorts', 'Shoes', 'Jackets', 'Pants', 'Accessories'];
        foreach ($categories as $category) {
            if (!term_exists($category, 'product_cat')) {
                wp_insert_term($category, 'product_cat');
            }
        }
        
        foreach ($sample_products as $product_data) {
            $post_id = wp_insert_post([
                'post_title' => $product_data['title'],
                'post_content' => 'Sample product description for ' . $product_data['title'],
                'post_status' => 'publish',
                'post_type' => 'product'
            ]);
            
            if ($post_id && !is_wp_error($post_id)) {
                update_post_meta($post_id, '_price', $product_data['price']);
                update_post_meta($post_id, 'product_type', $product_data['type']);
                
                $category_term = get_term_by('name', $product_data['category'], 'product_cat');
                if ($category_term) {
                    wp_set_post_terms($post_id, [$category_term->term_id], 'product_cat');
                }
            }
        }
    }
}

register_activation_hook(PRODUCT_FILTER_ELEMENTOR_PATH . 'product-filter-elementor.php', ['ProductFilterSetup', 'activate']);
register_deactivation_hook(PRODUCT_FILTER_ELEMENTOR_PATH . 'product-filter-elementor.php', ['ProductFilterSetup', 'deactivate']);