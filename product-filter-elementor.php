<?php
/**
 * Plugin Name: Product Filter Elementor
 * Description: Custom Elementor widgets for product filtering and display
 * Version: 1.0.7
 * Author: Your Name
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
        wp_enqueue_script('product-filter-js', PRODUCT_FILTER_ELEMENTOR_URL . 'assets/product-filter.js', ['jquery'], '1.0.0', true);
        wp_enqueue_style('product-filter-css', PRODUCT_FILTER_ELEMENTOR_URL . 'assets/product-filter.css', [], '1.0.0');
        wp_enqueue_style('product-header-css', PRODUCT_FILTER_ELEMENTOR_URL . 'assets/product-header.css', [], '1.0.0');
        
        wp_localize_script('product-filter-js', 'productFilter', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('product_filter_nonce')
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
        
        if (!empty($filters)) {
            $meta_query = ['relation' => 'AND'];
            foreach ($filters as $key => $values) {
                if (!empty($values)) {
                    $meta_query[] = [
                        'key' => $key,
                        'value' => $values,
                        'compare' => 'IN'
                    ];
                }
            }
            $args['meta_query'] = $meta_query;
        }
        
        $query = new WP_Query($args);
        
        ob_start();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $this->render_product_item();
            }
        }
        $products_html = ob_get_clean();
        
        wp_send_json_success([
            'products' => $products_html,
            'total_pages' => $query->max_num_pages,
            'current_page' => $page
        ]);
    }
    
    private function render_product_item() {
        global $post;
        $product_id = $post->ID;
        $price = get_post_meta($product_id, '_price', true);
        $image = get_the_post_thumbnail_url($product_id, 'medium');
        ?>
<div class="product-item">
    <div class="product-image">
        <img src="<?php echo esc_url($image); ?>" alt="<?php the_title(); ?>">
    </div>
    <div class="product-info">
        <h3 class="product-title"><?php the_title(); ?></h3>
        <div class="product-price">Rp <?php echo number_format($price, 0, ',', '.'); ?></div>
    </div>
</div>
<?php
    }
}

new ProductFilterElementor();