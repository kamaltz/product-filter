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
        
        $this->add_control(
            'toggle_icon_type',
            [
                'label' => 'Icon Type',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'preset',
                'options' => [
                    'preset' => 'Preset Icons',
                    'library' => 'Icon Library',
                    'upload' => 'Upload Custom',
                ],
                'condition' => [
                    'mobile_filter_toggle' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'toggle_icon',
            [
                'label' => 'Choose Icon',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'filter',
                'options' => [
                    'filter' => 'Filter Sliders',
                    'hamburger' => 'Hamburger Menu',
                    'grid' => 'Grid',
                    'settings' => 'Settings',
                ],
                'condition' => [
                    'mobile_filter_toggle' => 'yes',
                    'toggle_icon_type' => 'preset',
                ],
            ]
        );
        
        $this->add_control(
            'toggle_icon_library',
            [
                'label' => 'Icon',
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-filter',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'mobile_filter_toggle' => 'yes',
                    'toggle_icon_type' => 'library',
                ],
            ]
        );
        
        $this->add_control(
            'toggle_icon_upload',
            [
                'label' => 'Upload Icon',
                'type' => \Elementor\Controls_Manager::MEDIA,
                'condition' => [
                    'mobile_filter_toggle' => 'yes',
                    'toggle_icon_type' => 'upload',
                ],
            ]
        );
        
        $this->add_control(
            'toggle_icon_color',
            [
                'label' => 'Icon Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}} .filter-toggle-btn' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'mobile_filter_toggle' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'toggle_bg_color',
            [
                'label' => 'Background Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .filter-toggle-btn' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'mobile_filter_toggle' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'toggle_border_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 25,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .filter-toggle-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'mobile_filter_toggle' => 'yes',
                ],
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
        $icon_type = $settings['toggle_icon_type'] ?: 'preset';
        ?>
<?php if ($mobile_toggle): ?>
<div class="mobile-filter-toggle">
    <button class="filter-toggle-btn">
        <?php echo $this->render_toggle_icon($settings, $icon_type); ?>
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
        
        // Try WooCommerce brand taxonomies first
        $brand_taxonomies = ['pa_brand', 'product_brand', 'pwb-brand', 'yith_product_brand'];
        $brands = [];
        
        foreach ($brand_taxonomies as $taxonomy) {
            if (taxonomy_exists($taxonomy)) {
                $terms = get_terms([
                    'taxonomy' => $taxonomy,
                    'hide_empty' => true,
                ]);
                if (!is_wp_error($terms) && !empty($terms)) {
                    $brands = $terms;
                    break;
                }
            }
        }
        
        // If no taxonomy found, try meta fields
        if (empty($brands)) {
            $meta_brands = $wpdb->get_results(
                "SELECT DISTINCT pm.meta_value as name, pm.meta_value as slug
                 FROM {$wpdb->postmeta} pm
                 INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
                 WHERE pm.meta_key IN ('_product_brand', 'brand', '_brand')
                 AND pm.meta_value != ''
                 AND p.post_type = 'product'
                 AND p.post_status = 'publish'
                 ORDER BY pm.meta_value ASC"
            );
            $brands = $meta_brands;
        }
        
        foreach ($brands as $brand) {
            $brand_name = isset($brand->name) ? $brand->name : $brand->meta_value;
            $brand_slug = isset($brand->slug) ? $brand->slug : sanitize_title($brand_name);
            $brand_id = isset($brand->term_id) ? $brand->term_id : sanitize_title($brand_name);
            
            echo '<li class="filter-item">';
            echo '<label for="Filter-brand-' . $brand_id . '">';
            echo '<input type="checkbox" name="brand[]" value="' . esc_attr($brand_slug) . '" id="Filter-brand-' . $brand_id . '">';
            echo '<span class="box-check"></span>';
            echo '<span>' . esc_html($brand_name) . '</span>';
            echo '</label>';
            echo '</li>';
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
    
    private function render_toggle_icon($settings, $icon_type) {
        switch ($icon_type) {
            case 'library':
                if (!empty($settings['toggle_icon_library']['value'])) {
                    ob_start();
                    \Elementor\Icons_Manager::render_icon($settings['toggle_icon_library'], ['aria-hidden' => 'true']);
                    return ob_get_clean();
                }
                break;
            case 'upload':
                if (!empty($settings['toggle_icon_upload']['url'])) {
                    return '<img src="' . esc_url($settings['toggle_icon_upload']['url']) . '" alt="Filter">';
                }
                break;
            default:
                return $this->get_preset_icon($settings['toggle_icon'] ?: 'filter');
        }
        return $this->get_preset_icon('filter');
    }
    
    private function get_preset_icon($icon_type) {
        $icons = [
            'filter' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M3 7H21M3 12H21M3 17H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="21" cy="7" r="1" fill="currentColor"/><circle cx="3" cy="12" r="1" fill="currentColor"/><circle cx="21" cy="17" r="1" fill="currentColor"/></svg>',
            'hamburger' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M3 6H21M3 12H21M3 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
            'grid' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="7" height="7" stroke="currentColor" stroke-width="2" fill="none"/><rect x="14" y="3" width="7" height="7" stroke="currentColor" stroke-width="2" fill="none"/><rect x="3" y="14" width="7" height="7" stroke="currentColor" stroke-width="2" fill="none"/><rect x="14" y="14" width="7" height="7" stroke="currentColor" stroke-width="2" fill="none"/></svg>',
            'settings' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="currentColor" stroke-width="2"/><path d="M19.4 15C19.2669 15.3016 19.2272 15.6362 19.286 15.9606C19.3448 16.285 19.4995 16.5843 19.73 16.82L19.79 16.88C19.976 17.0657 20.1235 17.2863 20.2241 17.5291C20.3248 17.7719 20.3766 18.0322 20.3766 18.295C20.3766 18.5578 20.3248 18.8181 20.2241 19.0609C20.1235 19.3037 19.976 19.5243 19.79 19.71C19.6043 19.896 19.3837 20.0435 19.1409 20.1441C18.8981 20.2448 18.6378 20.2966 18.375 20.2966C18.1122 20.2966 17.8519 20.2448 17.6091 20.1441C17.3663 20.0435 17.1457 19.896 16.96 19.71L16.9 19.65C16.6643 19.4195 16.365 19.2648 16.0406 19.206C15.7162 19.1472 15.3816 19.1869 15.08 19.32C14.7842 19.4468 14.532 19.6572 14.3543 19.9255C14.1766 20.1938 14.0813 20.5082 14.08 20.83V21C14.08 21.5304 13.8693 22.0391 13.4942 22.4142C13.1191 22.7893 12.6104 23 12.08 23C11.5496 23 11.0409 22.7893 10.6658 22.4142C10.2907 22.0391 10.08 21.5304 10.08 21V20.91C10.0723 20.579 9.96512 20.2569 9.77251 19.9859C9.5799 19.7148 9.31074 19.5067 9 19.385C8.69838 19.2519 8.36381 19.2122 8.03941 19.271C7.71502 19.3298 7.41568 19.4845 7.18 19.715L7.12 19.775C6.93425 19.961 6.71368 20.1085 6.47088 20.2091C6.22808 20.3098 5.96783 20.3616 5.705 20.3616C5.44217 20.3616 5.18192 20.3098 4.93912 20.2091C4.69632 20.1085 4.47575 19.961 4.29 19.775C4.10405 19.5893 3.95653 19.3687 3.85588 19.1259C3.75523 18.8831 3.70343 18.6228 3.70343 18.36C3.70343 18.0972 3.75523 17.8369 3.85588 17.5941C3.95653 17.3513 4.10405 17.1307 4.29 16.945L4.35 16.885C4.58054 16.6493 4.73519 16.35 4.794 16.0256C4.85282 15.7012 4.81312 15.3666 4.68 15.065C4.55324 14.7692 4.34276 14.517 4.07447 14.3393C3.80618 14.1616 3.49179 14.0663 3.17 14.065H3C2.46957 14.065 1.96086 13.8543 1.58579 13.4792C1.21071 13.1041 1 12.5954 1 12.065C1 11.5346 1.21071 11.0259 1.58579 10.6508C1.96086 10.2757 2.46957 10.065 3 10.065H3.09C3.42099 10.0573 3.742 9.95012 4.01309 9.75751C4.28417 9.5649 4.49226 9.29574 4.614 8.985C4.74712 8.68338 4.78682 8.34881 4.728 8.02441C4.66919 7.70002 4.51454 7.40068 4.284 7.165L4.224 7.105C4.03805 6.91925 3.89053 6.69868 3.78988 6.45588C3.68923 6.21308 3.63743 5.95283 3.63743 5.69C3.63743 5.42717 3.68923 5.16692 3.78988 4.92412C3.89053 4.68132 4.03805 4.46075 4.224 4.275C4.40975 4.08905 4.63032 3.94153 4.87312 3.84088C5.11592 3.74023 5.37617 3.68843 5.639 3.68843C5.90183 3.68843 6.16208 3.74023 6.40488 3.84088C6.64768 3.94153 6.86825 4.08905 7.054 4.275L7.114 4.335C7.34968 4.56554 7.649 4.72019 7.97339 4.779C8.29779 4.83782 8.63236 4.79812 8.934 4.665H9C9.29577 4.53824 9.54802 4.32776 9.72569 4.05947C9.90337 3.79118 9.99872 3.47679 10 3.155V3C10 2.46957 10.2107 1.96086 10.5858 1.58579C10.9609 1.21071 11.4696 1 12 1C12.5304 1 13.0391 1.21071 13.4142 1.58579C13.7893 1.96086 14 2.46957 14 3V3.09C14.0013 3.41179 14.0966 3.72618 14.2743 3.99447C14.452 4.26276 14.7042 4.47324 15 4.6C15.3016 4.73312 15.6362 4.77282 15.9606 4.714C16.285 4.65519 16.5843 4.50054 16.82 4.27L16.88 4.21C17.0657 4.02405 17.2863 3.87653 17.5291 3.77588C17.7719 3.67523 18.0322 3.62343 18.295 3.62343C18.5578 3.62343 18.8181 3.67523 19.0609 3.77588C19.3037 3.87653 19.5243 4.02405 19.71 4.21C19.896 4.39575 20.0435 4.61632 20.1441 4.85912C20.2448 5.10192 20.2966 5.36217 20.2966 5.625C20.2966 5.88783 20.2448 6.14808 20.1441 6.39088C20.0435 6.63368 19.896 6.85425 19.71 7.04L19.65 7.1C19.4195 7.33568 19.2648 7.635 19.206 7.95939C19.1472 8.28379 19.1869 8.61836 19.32 8.92V9C19.4468 9.29577 19.6572 9.54802 19.9255 9.72569C20.1938 9.90337 20.5082 9.99872 20.83 10H21C21.5304 10 22.0391 10.2107 22.4142 10.5858C22.7893 10.9609 23 11.4696 23 12C23 12.5304 22.7893 13.0391 22.4142 13.4142C22.0391 13.7893 21.5304 14 21 14H20.91C20.5882 14.0013 20.2738 14.0966 20.0055 14.2743C19.7372 14.452 19.5268 14.7042 19.4 15Z" stroke="currentColor" stroke-width="2"/></svg>'
        ];
        
        return isset($icons[$icon_type]) ? $icons[$icon_type] : $icons['filter'];
    }
}