<?php
if (!defined('ABSPATH')) {
    exit;
}

class ProductDisplayWidget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'product-display';
    }
    
    public function get_title() {
        return 'Product Display';
    }
    
    public function get_icon() {
        return 'eicon-products';
    }
    
    public function get_categories() {
        return ['general'];
    }
    
    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Content',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'products_per_page',
            [
                'label' => 'Products Per Page',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 12,
                'min' => 1,
                'max' => 50,
            ]
        );
        
        $this->add_control(
            'show_product_count',
            [
                'label' => 'Show Product Count',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_sort_dropdown',
            [
                'label' => 'Show Sort Dropdown',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_responsive_control(
            'columns',
            [
                'label' => 'Columns',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'selectors' => [
                    '{{WRAPPER}} .products-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'gap',
            [
                'label' => 'Gap',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .products-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'show_pagination',
            [
                'label' => 'Show Pagination',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => 'Product Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'product_bg_color',
            [
                'label' => 'Product Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .product-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'product_border',
                'selector' => '{{WRAPPER}} .product-item',
            ]
        );
        
        $this->add_control(
            'product_border_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .product-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'product_shadow',
                'selector' => '{{WRAPPER}} .product-item',
            ]
        );
        
        $this->end_controls_section();
        
        // Typography Section
        $this->start_controls_section(
            'typography_section',
            [
                'label' => 'Typography',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => 'Title Typography',
                'selector' => '{{WRAPPER}} .product-title',
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => 'Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .product-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'label' => 'Price Typography',
                'selector' => '{{WRAPPER}} .product-price',
            ]
        );
        
        $this->add_control(
            'price_color',
            [
                'label' => 'Price Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .product-price' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $args = [
            'post_type' => 'product',
            'posts_per_page' => $settings['products_per_page'],
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC'
        ];
        
        $query = new WP_Query($args);
        $total_products = $query->found_posts;
        ?>
<div class="product-display-container">

    <?php if ($settings['show_product_count'] === 'yes' || $settings['show_sort_dropdown'] === 'yes'): ?>
    <div class="products-header">
        <?php if ($settings['show_product_count'] === 'yes'): ?>
        <div class="products-count">
            <?php 
                        $current_page = max(1, get_query_var('paged'));
                        $start_item = (($current_page - 1) * $settings['products_per_page']) + 1;
                        $end_item = min($current_page * $settings['products_per_page'], $total_products);
                        
                        if ($total_products > 0) {
                            echo sprintf('Showing %d–%d of %d products', $start_item, $end_item, $total_products);
                        } else {
                            echo 'No products found';
                        }
                        ?>
        </div>
        <?php endif; ?>

        <?php if ($settings['show_sort_dropdown'] === 'yes'): ?>
        <div class="products-sort">
            <select class="sort-dropdown" name="sort_by_dropdown">
                <option value="featured">Sort by: Featured</option>
                <option value="newest">Sort by: Newest first</option>
                <option value="price_asc">Sort by: Price, low to high</option>
                <option value="price_desc">Sort by: Price, high to low</option>
                <option value="name_asc">Sort by: Alphabetically, A-Z</option>
                <option value="name_desc">Sort by: Alphabetically, Z-A</option>
            </select>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="products-grid" data-per-page="<?php echo esc_attr($settings['products_per_page']); ?>">
        <?php
                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        $this->render_product_item();
                    }
                } else {
                    echo '<div class="no-products">No products found matching your criteria.</div>';
                }
                wp_reset_postdata();
                ?>
    </div>

    <?php if ($settings['show_pagination'] === 'yes' && $query->max_num_pages > 1): ?>
    <div class="product-pagination">
        <?php
                $current_page = max(1, get_query_var('paged', 1));
                $max_pages = $query->max_num_pages;
                
                // Previous button
                if ($current_page > 1) {
                    echo '<button class="page-btn page-prev" data-page="' . ($current_page - 1) . '">';
                    echo '<svg width="8" height="14" viewBox="0 0 8 14" fill="none"><path d="M7 1L1 7L7 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                    echo '</button>';
                }
                
                // Page numbers
                $start_page = max(1, $current_page - 2);
                $end_page = min($max_pages, $current_page + 2);
                
                if ($start_page > 1) {
                    echo '<button class="page-btn" data-page="1">1</button>';
                    if ($start_page > 2) {
                        echo '<span class="page-dots">...</span>';
                    }
                }
                
                for ($i = $start_page; $i <= $end_page; $i++) {
                    $active_class = ($i === $current_page) ? 'active' : '';
                    echo '<button class="page-btn ' . $active_class . '" data-page="' . $i . '">' . $i . '</button>';
                }
                
                if ($end_page < $max_pages) {
                    if ($end_page < $max_pages - 1) {
                        echo '<span class="page-dots">...</span>';
                    }
                    echo '<button class="page-btn" data-page="' . $max_pages . '">' . $max_pages . '</button>';
                }
                
                // Next button
                if ($current_page < $max_pages) {
                    echo '<button class="page-btn page-next" data-page="' . ($current_page + 1) . '">';
                    echo '<svg width="8" height="14" viewBox="0 0 8 14" fill="none"><path d="M1 1L7 7L1 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                    echo '</button>';
                }
                ?>
    </div>
    <?php endif; ?>
</div>
<?php
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
    <div class="product-image">
        <?php if ($is_new): ?>
        <div class="product-badge product-badge-new">New</div>
        <?php elseif ($on_sale): ?>
        <div class="product-badge product-badge-sale">Sale</div>
        <?php endif; ?>

        <img src="<?php echo $image ? esc_url($image) : $placeholder; ?>" alt="<?php the_title(); ?>"
            class="product-main-image" <?php if ($hover_image): ?> data-hover="<?php echo esc_url($hover_image); ?>"
            <?php endif; ?>>

        <div class="product-overlay">
            <button class="product-quick-view" data-product-id="<?php echo $product_id; ?>">Quick View</button>
            <button class="product-add-to-cart" data-product-id="<?php echo $product_id; ?>">Add to Cart</button>
        </div>
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