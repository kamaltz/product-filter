<?php
if (!defined('ABSPATH')) {
    exit;
}

class ProductFilterWidget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'product-filter';
    }
    
    public function get_title() {
        return 'Product Filter';
    }
    
    public function get_icon() {
        return 'eicon-filter';
    }
    
    public function get_categories() {
        return ['general'];
    }
    
    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Filter Configuration',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'filter_order',
            [
                'label' => 'Filter Order & Visibility',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'filter_type',
                        'label' => 'Filter Type',
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'options' => [
                            'sort_by' => 'Sort By',
                            'categories' => 'Product Categories',
                            'brands' => 'Brands (WooCommerce)',
                            'price_range' => 'Price Range',
                            'product_type' => 'Product Type',
                            'sizes' => 'Sizes',
                            'colors' => 'Colors',
                        ],
                        'default' => 'sort_by',
                    ],
                    [
                        'name' => 'filter_title',
                        'label' => 'Custom Title',
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => 'Leave empty for default title',
                    ],
                    [
                        'name' => 'is_active',
                        'label' => 'Show Filter',
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'default' => 'yes',
                    ],
                    [
                        'name' => 'default_open',
                        'label' => 'Open by Default',
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'default' => 'no',
                    ],
                ],
                'default' => [
                    [
                        'filter_type' => 'sort_by',
                        'filter_title' => 'Sort by',
                        'is_active' => 'yes',
                        'default_open' => 'yes',
                    ],
                    [
                        'filter_type' => 'sizes',
                        'filter_title' => 'Shoe size',
                        'is_active' => 'yes',
                        'default_open' => 'no',
                    ],
                    [
                        'filter_type' => 'categories',
                        'filter_title' => 'Product Type',
                        'is_active' => 'yes',
                        'default_open' => 'no',
                    ],
                    [
                        'filter_type' => 'brands',
                        'filter_title' => 'Brand',
                        'is_active' => 'yes',
                        'default_open' => 'no',
                    ],
                    [
                        'filter_type' => 'price_range',
                        'filter_title' => 'Price',
                        'is_active' => 'yes',
                        'default_open' => 'no',
                    ],
                ],
                'title_field' => '{{{ filter_title || filter_type }}}',
            ]
        );
        
        $this->end_controls_section();
        
        // Price Range Section
        $this->start_controls_section(
            'price_range_section',
            [
                'label' => 'Price Range Settings',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'price_ranges',
            [
                'label' => 'Price Ranges',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'price_label',
                        'label' => 'Price Label',
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => 'e.g., Under Rp 100,000',
                    ],
                    [
                        'name' => 'price_min',
                        'label' => 'Min Price',
                        'type' => \Elementor\Controls_Manager::NUMBER,
                        'default' => 0,
                    ],
                    [
                        'name' => 'price_max',
                        'label' => 'Max Price',
                        'type' => \Elementor\Controls_Manager::NUMBER,
                        'default' => 999999999,
                    ],
                ],
                'default' => [
                    ['price_label' => 'Under Rp 200,000', 'price_min' => 0, 'price_max' => 200000],
                    ['price_label' => 'Rp 200,000 - Rp 500,000', 'price_min' => 200000, 'price_max' => 500000],
                    ['price_label' => 'Rp 500,000 - Rp 1,000,000', 'price_min' => 500000, 'price_max' => 1000000],
                    ['price_label' => 'Above Rp 1,000,000', 'price_min' => 1000000, 'price_max' => 999999999],
                ],
                'title_field' => '{{{ price_label }}}',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => 'Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'filter_bg_color',
            [
                'label' => 'Background Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .filter-custom' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'filter_text_color',
            [
                'label' => 'Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .filter-custom' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'filter_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .filter-custom' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
                'name' => 'filter_title_typography',
                'label' => 'Filter Title Typography',
                'selector' => '{{WRAPPER}} .filter-title',
            ]
        );
        
        $this->add_control(
            'filter_title_color',
            [
                'label' => 'Filter Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .filter-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'filter_item_typography',
                'label' => 'Filter Item Typography',
                'selector' => '{{WRAPPER}} .filter-item label span:last-child',
            ]
        );
        
        $this->add_control(
            'filter_item_color',
            [
                'label' => 'Filter Item Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .filter-item label' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'filter_item_active_color',
            [
                'label' => 'Filter Item Active Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .filter-item.filter-active label' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .filter-item input:checked + .box-check + span' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'size_swatch_typography',
                'label' => 'Size Swatch Typography',
                'selector' => '{{WRAPPER}} .size-swatch .swatch-label',
            ]
        );
        
        $this->end_controls_section();
        
        // Mobile Settings
        $this->start_controls_section(
            'mobile_section',
            [
                'label' => 'Mobile Settings',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'mobile_filter_toggle',
            [
                'label' => 'Mobile Filter Toggle',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => 'Show filter toggle button on mobile',
            ]
        );
        
        $this->add_control(
            'mobile_filter_text',
            [
                'label' => 'Filter Button Text',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Filter',
                'condition' => [
                    'mobile_filter_toggle' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'sticky_desktop',
            [
                'label' => 'Sticky on Desktop',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );
        
        $this->add_control(
            'sticky_mobile',
            [
                'label' => 'Sticky on Mobile',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $filter_order = $settings['filter_order'];
        $price_ranges = $settings['price_ranges'];
        $mobile_toggle = $settings['mobile_filter_toggle'] === 'yes';
        $filter_text = $settings['mobile_filter_text'] ?: 'Filter';
        ?>
<?php if ($mobile_toggle): ?>
<div class="mobile-filter-toggle">
    <button class="filter-toggle-btn">
        <svg width="39" height="32" viewBox="0 0 39 32" fill="none">
            <path d="M25.6054 16.959C21.5723 16.959 17.5376 16.9697 13.5045 16.9452C12.9701 16.9421 12.7438 17.0911 12.5567 17.5886C11.895 19.3422 10.1975 20.4555 8.30983 20.454C6.42372 20.454 4.75609 19.3514 4.07709 17.5717C3.88534 17.068 3.63857 16.9083 3.11361 16.9421C2.43618 16.9866 1.75246 16.9575 1.07189 16.9513C0.421186 16.9452 0.00152706 16.6427 0.00938584 15.9854C0.0172446 15.3236 0.455765 15.0472 1.09861 15.0441C1.83262 15.0411 2.56663 15.0226 3.29907 15.0518C3.71087 15.0687 3.88062 14.9136 4.02522 14.542C4.77181 12.6425 6.39072 11.5369 8.33498 11.543C10.2478 11.5491 11.895 12.6594 12.5866 14.5051C12.7799 15.0226 13.0833 15.0441 13.5296 15.0441C20.6277 15.0364 27.7257 15.0411 34.8238 15.0426C35.8187 15.0426 36.8152 15.0503 37.8101 15.0411C38.4766 15.0349 38.9481 15.2622 38.9952 15.9731C39.0361 16.5889 38.5866 16.9482 37.7834 16.9498C33.7236 16.9544 29.6637 16.9513 25.6039 16.9513V16.9559L25.6054 16.959ZM8.35541 13.4855C6.91726 13.4686 5.74158 14.5881 5.73058 15.9839C5.71958 17.349 6.84181 18.4777 8.24382 18.513C9.69612 18.5499 10.8671 17.4627 10.8954 16.0545C10.9237 14.6403 9.79986 13.5039 8.35541 13.4855Z" fill="black"/>
        </svg>
    </button>
</div>
<?php endif; ?>
<?php 
$sticky_classes = [];
if ($settings['sticky_desktop'] === 'yes') $sticky_classes[] = 'sticky-desktop';
if ($settings['sticky_mobile'] === 'yes') $sticky_classes[] = 'sticky-mobile';
$sticky_class = implode(' ', $sticky_classes);
?>
<div class="filter-custom <?php echo $mobile_toggle ? 'mobile-hidden' : ''; ?> <?php echo esc_attr($sticky_class); ?>">
    <div class="inner-filter">
        <div class="inner-filter--app">
            <?php if ($mobile_toggle): ?>
            <div class="mobile-filter-header">
                <h3>Filters</h3>
                <button class="filter-close-btn">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
            <?php endif; ?>

            <?php foreach ($filter_order as $filter): ?>
            <?php if ($filter['is_active'] === 'yes'): ?>
            <?php $this->render_filter_group($filter, $price_ranges); ?>
            <?php endif; ?>
            <?php endforeach; ?>

        </div>
    </div>
</div>
<?php
    }
    
    private function render_filter_group($filter, $price_ranges) {
        $filter_type = $filter['filter_type'];
        $title = !empty($filter['filter_title']) ? $filter['filter_title'] : $this->get_default_title($filter_type);
        $default_open = $filter['default_open'] === 'yes' ? 'active' : '';
        
        echo '<div class="filter-group ' . $default_open . '">';
        echo '<span class="filter-title filter-button">' . esc_html($title) . '</span>';
        echo '<div class="filter-panel">';
        echo '<ul class="filter-size-group filter-list">';
        
        switch ($filter_type) {
            case 'sort_by':
                $this->render_sort_filter();
                break;
            case 'categories':
                $this->render_categories_filter();
                break;
            case 'brands':
                $this->render_brands_filter();
                break;
            case 'price_range':
                $this->render_price_range_filter($price_ranges);
                break;
            case 'product_type':
                $this->render_product_type_filter();
                break;
            case 'sizes':
                $this->render_sizes_filter();
                break;
            case 'colors':
                $this->render_colors_filter();
                break;
        }
        
        echo '</ul>';
        echo '</div>';
        echo '</div>';
    }
    
    private function get_default_title($filter_type) {
        $titles = [
            'sort_by' => 'Sort by',
            'categories' => 'Categories',
            'brands' => 'Brand',
            'price_range' => 'Price',
            'product_type' => 'Product Type',
            'sizes' => 'Size',
            'colors' => 'Color',
        ];
        return isset($titles[$filter_type]) ? $titles[$filter_type] : ucfirst($filter_type);
    }
    
    private function render_sort_filter() {
        $sort_options = [
            'featured' => 'Featured',
            'newest' => 'Newest first',
            'price_asc' => 'Price: low to high',
            'price_desc' => 'Price: high to low',
        ];
        
        $first = true;
        foreach ($sort_options as $value => $label) {
            $checked = $first ? 'checked' : '';
            $active_class = $first ? 'filter-active' : '';
            echo '<li class="filter-item ' . $active_class . '">';
            echo '<label for="Filter-sort-' . $value . '" class="filter-sort-by">';
            echo '<span class="box-check"></span>';
            echo '<input type="radio" name="sort_by" value="' . $value . '" id="Filter-sort-' . $value . '" ' . $checked . '>';
            echo '<span>' . esc_html($label) . '</span>';
            echo '</label>';
            echo '</li>';
            $first = false;
        }
    }
    
    private function render_categories_filter() {
        $categories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
        ]);
        
        if (!is_wp_error($categories) && !empty($categories)) {
            foreach ($categories as $category) {
                // Check if category has products
                if ($category->count > 0) {
                    echo '<li class="filter-item">';
                    echo '<label for="Filter-category-' . $category->term_id . '">';
                    echo '<input type="checkbox" name="category[]" value="' . $category->slug . '" id="Filter-category-' . $category->term_id . '">';
                    echo '<span class="box-check"></span>';
                    echo '<span>' . esc_html($category->name) . '</span>';
                    echo '</label>';
                    echo '</li>';
                }
            }
        }
    }
    
    private function render_brands_filter() {
        global $wpdb;
        
        // Get brands from WooCommerce products
        $brands = $wpdb->get_results(
            "SELECT DISTINCT meta_value as brand_name 
             FROM {$wpdb->postmeta} pm
             INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
             WHERE pm.meta_key = '_product_brand' 
             AND pm.meta_value != ''
             AND p.post_type = 'product'
             AND p.post_status = 'publish'
             ORDER BY pm.meta_value ASC"
        );
        
        // Fallback to taxonomy if meta field doesn't exist
        if (empty($brands)) {
            $brand_terms = get_terms([
                'taxonomy' => 'pa_brand',
                'hide_empty' => true,
            ]);
            
            if (!is_wp_error($brand_terms) && !empty($brand_terms)) {
                foreach ($brand_terms as $brand) {
                    echo '<li class="filter-item">';
                    echo '<label for="Filter-brand-' . $brand->term_id . '">';
                    echo '<input type="checkbox" name="brand[]" value="' . $brand->slug . '" id="Filter-brand-' . $brand->term_id . '">';
                    echo '<span class="box-check"></span>';
                    echo '<span>' . esc_html($brand->name) . '</span>';
                    echo '</label>';
                    echo '</li>';
                }
            }
        } else {
            foreach ($brands as $brand) {
                echo '<li class="filter-item">';
                echo '<label for="Filter-brand-' . sanitize_title($brand->brand_name) . '">';
                echo '<input type="checkbox" name="brand[]" value="' . esc_attr($brand->brand_name) . '" id="Filter-brand-' . sanitize_title($brand->brand_name) . '">';
                echo '<span class="box-check"></span>';
                echo '<span>' . esc_html($brand->brand_name) . '</span>';
                echo '</label>';
                echo '</li>';
            }
        }
    }
    
    private function render_price_range_filter($price_ranges) {
        foreach ($price_ranges as $range) {
            $value = $range['price_min'] . '-' . $range['price_max'];
            echo '<li class="filter-item">';
            echo '<label for="Filter-price-' . md5($value) . '">';
            echo '<input type="checkbox" name="price_range[]" value="' . $value . '" id="Filter-price-' . md5($value) . '">';
            echo '<span class="box-check"></span>';
            echo '<span>' . esc_html($range['price_label']) . '</span>';
            echo '</label>';
            echo '</li>';
        }
    }
    
    private function render_product_type_filter() {
        $product_types = ['Men', 'Women', 'Unisex', 'Kids'];
        
        foreach ($product_types as $type) {
            echo '<li class="filter-item">';
            echo '<label for="Filter-type-' . sanitize_title($type) . '">';
            echo '<input type="checkbox" name="product_type[]" value="' . $type . '" id="Filter-type-' . sanitize_title($type) . '">';
            echo '<span class="box-check"></span>';
            echo '<span>' . esc_html($type) . '</span>';
            echo '</label>';
            echo '</li>';
        }
    }
    
    private function render_sizes_filter() {
        $sizes = get_terms([
            'taxonomy' => 'pa_size',
            'hide_empty' => true,
        ]);
        
        if (is_wp_error($sizes) || empty($sizes)) {
            $sizes = get_terms([
                'taxonomy' => 'pa_shoe-size',
                'hide_empty' => true,
            ]);
        }
        
        echo '</ul><div class="size-swatches">';
        if (!is_wp_error($sizes) && !empty($sizes)) {
            foreach ($sizes as $size) {
                // Only show sizes that have products
                if ($size->count > 0) {
                    echo '<label class="size-swatch" for="Filter-size-' . $size->term_id . '">';
                    echo '<input type="checkbox" name="shoe_size[]" value="' . $size->slug . '" id="Filter-size-' . $size->term_id . '">';
                    echo '<span class="swatch-label">' . esc_html($size->name) . '</span>';
                    echo '</label>';
                }
            }
        } else {
            // Fallback with common shoe sizes if no taxonomy terms exist
            $common_sizes = ['5', '5.5', '6', '6.5', '7', '7.5', '8', '8.5', '9', '9.5', '10', '10.5', '11', '11.5', '12'];
            foreach ($common_sizes as $size) {
                echo '<label class="size-swatch" for="Filter-size-common-' . str_replace('.', '-', $size) . '">';
                echo '<input type="checkbox" name="shoe_size[]" value="us-' . $size . '" id="Filter-size-common-' . str_replace('.', '-', $size) . '">';
                echo '<span class="swatch-label">US ' . $size . '</span>';
                echo '</label>';
            }
        }
        echo '</div><ul class="filter-list" style="display:none;">';
    }
    
    private function render_colors_filter() {
        $colors = [
            'black' => 'Black',
            'white' => 'White',
            'red' => 'Red',
            'blue' => 'Blue',
            'green' => 'Green',
            'yellow' => 'Yellow',
        ];
        
        foreach ($colors as $value => $label) {
            echo '<li class="filter-item">';
            echo '<label for="Filter-color-' . $value . '">';
            echo '<input type="checkbox" name="color[]" value="' . $value . '" id="Filter-color-' . $value . '">';
            echo '<span class="box-check"></span>';
            echo '<span>' . esc_html($label) . '</span>';
            echo '</label>';
            echo '</li>';
        }
    }
}