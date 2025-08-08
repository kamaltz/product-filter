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
                    'size' => 20,
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
        
        $this->add_control(
            'show_sorting',
            [
                'label' => 'Show Sorting',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
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
                'default' => '#333333',
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
                'default' => '#e74c3c',
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
        ?>
        <div class="product-display-container">
            <div class="products-header">
                <div class="products-count">
                    <?php echo $query->found_posts; ?> Products
                </div>
                <div class="products-sort">
                    <select class="sort-dropdown">
                        <option value="date-desc">Newest First</option>
                        <option value="date-asc">Oldest First</option>
                        <option value="price-asc">Price: Low to High</option>
                        <option value="price-desc">Price: High to Low</option>
                        <option value="title-asc">Name: A to Z</option>
                        <option value="title-desc">Name: Z to A</option>
                    </select>
                </div>
            </div>
            
            <div class="products-grid" data-per-page="<?php echo esc_attr($settings['products_per_page']); ?>">
                <?php
                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        $this->render_product_item();
                    }
                } else {
                    echo '<p class="no-products">No products found.</p>';
                }
                wp_reset_postdata();
                ?>
            </div>
            
            <?php if ($settings['show_pagination'] === 'yes' && $query->max_num_pages > 1): ?>
            <div class="product-pagination">
                <?php
                for ($i = 1; $i <= $query->max_num_pages; $i++) {
                    $active_class = ($i === 1) ? 'active' : '';
                    echo '<button class="page-btn ' . $active_class . '" data-page="' . $i . '">' . $i . '</button>';
                }
                ?>
                <div class="pagination-info">
                    Showing <?php echo (($query->get('paged', 1) - 1) * $settings['products_per_page'] + 1); ?> - 
                    <?php echo min($query->get('paged', 1) * $settings['products_per_page'], $query->found_posts); ?> 
                    of <?php echo $query->found_posts; ?> products
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php
    }
    
    private function render_product_item() {
        global $post;
        $product_id = $post->ID;
        $price = get_post_meta($product_id, '_price', true);
        $product_type = get_post_meta($product_id, 'product_type', true);
        $image = get_the_post_thumbnail_url($product_id, 'medium');
        $placeholder = 'data:image/svg+xml;base64,' . base64_encode('<svg width="300" height="375" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#f8f8f8"/><text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#ccc" font-size="14">No Image</text></svg>');
        
        // Check if product is new (created within last 30 days)
        $is_new = (time() - strtotime($post->post_date)) < (30 * 24 * 60 * 60);
        ?>
        <div class="product-item" data-product-id="<?php echo $product_id; ?>">
            <div class="product-image">
                <?php if ($is_new): ?>
                    <div class="product-badge">New</div>
                <?php endif; ?>
                <img src="<?php echo $image ? esc_url($image) : $placeholder; ?>" alt="<?php the_title(); ?>">
                <button class="product-quick-view" data-product-id="<?php echo $product_id; ?>">Quick View</button>
            </div>
            <div class="product-info">
                <h3 class="product-title"><?php the_title(); ?></h3>
                <div class="product-price">
                    <?php if ($price): ?>
                        Rp <?php echo number_format($price, 0, ',', '.'); ?>
                    <?php else: ?>
                        Contact for Price
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
}