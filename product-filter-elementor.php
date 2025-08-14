<?php
/**
 * Plugin Name: Product Filter Elementor
 * Description: Advanced Elementor widgets for product filtering and display with WooCommerce integration
 * Version: 3.0.0
 * Author: kamaltz
 */

if (!defined('ABSPATH')) {
    exit;
}

define('PRODUCT_FILTER_ELEMENTOR_URL', plugin_dir_url(__FILE__));
define('PRODUCT_FILTER_ELEMENTOR_PATH', plugin_dir_path(__FILE__));

require_once PRODUCT_FILTER_ELEMENTOR_PATH . 'includes/setup.php';

class ProductFilterElementor {
    
    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }
    
    public function init() {
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_elementor']);
            return;
        }
        
        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        
        // AJAX handlers
        add_action('wp_ajax_filter_products', [$this, 'ajax_filter_products']);
        add_action('wp_ajax_nopriv_filter_products', [$this, 'ajax_filter_products']);

    }
    
    public function admin_notice_missing_elementor() {
        echo '<div class="notice notice-warning is-dismissible"><p>Product Filter Elementor requires Elementor to be installed and activated.</p></div>';
    }
    
    public function register_widgets() {
        require_once PRODUCT_FILTER_ELEMENTOR_PATH . 'widgets/product-filter-widget.php';
        require_once PRODUCT_FILTER_ELEMENTOR_PATH . 'widgets/product-display-widget.php';
        
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \ProductFilterWidget());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \ProductDisplayWidget());
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script('product-filter-js', PRODUCT_FILTER_ELEMENTOR_URL . 'assets/product-filter.js', ['jquery'], '2.0.0', true);
        wp_enqueue_style('product-filter-css', PRODUCT_FILTER_ELEMENTOR_URL . 'assets/product-filter.css', [], '2.0.0');
        wp_enqueue_style('product-header-css', PRODUCT_FILTER_ELEMENTOR_URL . 'assets/product-header.css', [], '2.0.0');
        
        wp_localize_script('product-filter-js', 'productFilter', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('product_filter_nonce'),
            'wc_active' => class_exists('WooCommerce')
        ]);
    }
    
    public function ajax_filter_products() {
        check_ajax_referer('product_filter_nonce', 'nonce');
        
        $filters = isset($_POST['filters']) ? $_POST['filters'] : [];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 12;
        
        $args = [
            'post_type' => 'product',
            'posts_per_page' => $per_page,
            'paged' => $page,
            'post_status' => 'publish'
        ];
        
        // Handle sorting
        if (!empty($filters['sort_by'])) {
            switch ($filters['sort_by']) {
                case 'newest':
                    $args['orderby'] = 'date';
                    $args['order'] = 'DESC';
                    break;
                case 'price_asc':
                    $args['meta_key'] = '_price';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'ASC';
                    break;
                case 'price_desc':
                    $args['meta_key'] = '_price';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                    break;
                case 'name_asc':
                    $args['orderby'] = 'title';
                    $args['order'] = 'ASC';
                    break;
                case 'name_desc':
                    $args['orderby'] = 'title';
                    $args['order'] = 'DESC';
                    break;
                default: // featured
                    $args['orderby'] = 'menu_order';
                    $args['order'] = 'ASC';
            }
        }
        
        // Handle taxonomy filters
        $tax_query = ['relation' => 'AND'];
        
        if (!empty($filters['categories'])) {
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $filters['categories'],
                'operator' => 'IN'
            ];
        }
        
        if (!empty($filters['brands'])) {
            // Try different brand taxonomies
            $brand_taxonomy = 'pa_brand';
            if (!taxonomy_exists($brand_taxonomy)) {
                $brand_taxonomy = 'product_brand';
            }
            
            if (taxonomy_exists($brand_taxonomy)) {
                $tax_query[] = [
                    'taxonomy' => $brand_taxonomy,
                    'field' => 'slug',
                    'terms' => $filters['brands'],
                    'operator' => 'IN'
                ];
            }
        }
        
        if (!empty($filters['colors'])) {
            $tax_query[] = [
                'taxonomy' => 'pa_color',
                'field' => 'slug',
                'terms' => $filters['colors'],
                'operator' => 'IN'
            ];
        }
        
        if (!empty($filters['sizes'])) {
            // Try different size taxonomies
            $size_taxonomy = 'pa_size';
            if (!taxonomy_exists($size_taxonomy)) {
                $size_taxonomy = 'pa_shoe-size';
            }
            
            if (taxonomy_exists($size_taxonomy)) {
                $tax_query[] = [
                    'taxonomy' => $size_taxonomy,
                    'field' => 'slug',
                    'terms' => $filters['sizes'],
                    'operator' => 'IN'
                ];
            } else {
                // Fallback to meta query for custom size fields
                $meta_query[] = [
                    'key' => '_shoe_size',
                    'value' => $filters['sizes'],
                    'compare' => 'IN'
                ];
            }
        }
        
        if (count($tax_query) > 1) {
            $args['tax_query'] = $tax_query;
        }
        
        // Handle meta filters
        $meta_query = ['relation' => 'AND'];
        
        if (!empty($filters['product_types'])) {
            $meta_query[] = [
                'key' => 'product_type',
                'value' => $filters['product_types'],
                'compare' => 'IN'
            ];
        }
        
        if (!empty($filters['price_ranges'])) {
            $price_meta_query = ['relation' => 'OR'];
            foreach ($filters['price_ranges'] as $range) {
                $parts = explode('-', $range);
                if (count($parts) === 2) {
                    $min_price = intval($parts[0]);
                    $max_price = intval($parts[1]);
                    
                    if ($max_price >= 999999999) {
                        // For "Above X" ranges
                        $price_meta_query[] = [
                            'key' => '_price',
                            'value' => $min_price,
                            'type' => 'NUMERIC',
                            'compare' => '>='
                        ];
                    } else {
                        // For specific ranges
                        $price_meta_query[] = [
                            'key' => '_price',
                            'value' => [$min_price, $max_price],
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN'
                        ];
                    }
                }
            }
            
            if (count($price_meta_query) > 1) {
                $meta_query[] = $price_meta_query;
            }
        }
        
        if (count($meta_query) > 1) {
            $args['meta_query'] = $meta_query;
        }
        
        $query = new WP_Query($args);
        
        ob_start();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $this->render_product_item();
            }
        } else {
            echo '<div class="no-products">No products found matching your criteria.</div>';
        }
        $products_html = ob_get_clean();
        wp_reset_postdata();
        
        wp_send_json_success([
            'products' => $products_html,
            'total_pages' => $query->max_num_pages,
            'current_page' => $page,
            'total_products' => $query->found_posts
        ]);
    }
    

    
    private function render_product_item() {
        global $post;
        $product_id = $post->ID;
        $price = get_post_meta($product_id, '_price', true);
        $regular_price = get_post_meta($product_id, '_regular_price', true);
        $sale_price = get_post_meta($product_id, '_sale_price', true);
        $product_type = get_post_meta($product_id, 'product_type', true);
        $image = get_the_post_thumbnail_url($product_id, 'medium');
        $placeholder = 'data:image/svg+xml;base64,' . base64_encode('<svg width="300" height="375" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#f8f8f8"/><text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#ccc" font-size="14">No Image</text></svg>');
        
        // Check if product is new (created within last 30 days)
        $is_new = (time() - strtotime($post->post_date)) < (30 * 24 * 60 * 60);
        $on_sale = !empty($sale_price) && $sale_price < $regular_price;
        
        // Get product gallery for hover effect
        $attachment_ids = get_post_meta($product_id, '_product_image_gallery', true);
        $hover_image = '';
        if ($attachment_ids) {
            $attachment_ids = explode(',', $attachment_ids);
            if (!empty($attachment_ids[0])) {
                $hover_image = wp_get_attachment_image_url($attachment_ids[0], 'medium');
            }
        }
        ?>
<div class="product-item" data-product-id="<?php echo $product_id; ?>">
    <div class="pf-product-image">
        <?php if ($is_new): ?>
        <div class="product-badge product-badge-new">New</div>
        <?php elseif ($on_sale): ?>
        <div class="product-badge product-badge-sale">Sale</div>
        <?php endif; ?>

        <img src="<?php echo $image ? esc_url($image) : $placeholder; ?>" alt="<?php the_title(); ?>"
            class="main-image">
        <?php if ($hover_image): ?>
        <img src="<?php echo esc_url($hover_image); ?>" alt="<?php the_title(); ?>" class="hover-image">
        <?php endif; ?>


    </div>
    <div class="product-info">
        <h3 class="product-title"><?php the_title(); ?></h3>
        <div class="product-price">
            <?php if ($on_sale && $regular_price): ?>
            <span class="price-sale">Rp <?php echo number_format($sale_price, 0, ',', '.'); ?></span>
            <span class="price-regular">Rp <?php echo number_format($regular_price, 0, ',', '.'); ?></span>
            <?php elseif ($price): ?>
            <span class="price-current">Rp <?php echo number_format($price, 0, ',', '.'); ?></span>
            <?php else: ?>
            <span class="price-contact">Contact for Price</span>
            <?php endif; ?>
        </div>

        <?php if ($product_type): ?>
        <div class="product-type"><?php echo esc_html($product_type); ?></div>
        <?php endif; ?>

        <!-- Color swatches if available -->
        <?php
                $colors = get_the_terms($product_id, 'pa_color');
                if ($colors && !is_wp_error($colors)):
                ?>
        <div class="product-colors">
            <?php foreach (array_slice($colors, 0, 4) as $color): ?>
            <span class="color-swatch" style="background-color: <?php echo esc_attr(strtolower($color->name)); ?>;"
                title="<?php echo esc_attr($color->name); ?>"></span>
            <?php endforeach; ?>
            <?php if (count($colors) > 4): ?>
            <span class="color-more">+<?php echo count($colors) - 4; ?></span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php
    }
    

}

new ProductFilterElementor();