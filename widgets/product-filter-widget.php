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
                'label' => 'Content',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'filter_title',
            [
                'label' => 'Filter Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Filter Products',
            ]
        );
        
        $this->add_control(
            'show_category_filter',
            [
                'label' => 'Show Category Filter',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_type_filter',
            [
                'label' => 'Show Type Filter',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_price_filter',
            [
                'label' => 'Show Price Filter',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
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
                    '{{WRAPPER}} .product-filter' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .product-filter' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .product-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="product-filter">
            <h3 class="filter-title"><?php echo esc_html($settings['filter_title']); ?></h3>
            
            <?php if ($settings['show_category_filter'] === 'yes'): ?>
            <div class="filter-group">
                <h4>Category</h4>
                <div class="filter-options">
                    <?php
                    $categories = get_terms([
                        'taxonomy' => 'product_cat',
                        'hide_empty' => false,
                    ]);
                    foreach ($categories as $category): ?>
                        <label class="filter-option">
                            <input type="checkbox" name="category[]" value="<?php echo $category->term_id; ?>">
                            <span><?php echo $category->name; ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if ($settings['show_type_filter'] === 'yes'): ?>
            <div class="filter-group">
                <h4>Gender</h4>
                <div class="filter-options">
                    <label class="filter-option">
                        <input type="checkbox" name="product_type[]" value="Men">
                        <span>Men</span>
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="product_type[]" value="Women">
                        <span>Women</span>
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="product_type[]" value="Unisex">
                        <span>Unisex</span>
                    </label>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if ($settings['show_price_filter'] === 'yes'): ?>
            <div class="filter-group">
                <h4>Price Range</h4>
                <div class="filter-options">
                    <label class="filter-option">
                        <input type="checkbox" name="price_range[]" value="0-100000">
                        <span>Under Rp 100,000</span>
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="price_range[]" value="100000-500000">
                        <span>Rp 100,000 - 500,000</span>
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="price_range[]" value="500000-1000000">
                        <span>Rp 500,000 - 1,000,000</span>
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="price_range[]" value="1000000-999999999">
                        <span>Above Rp 1,000,000</span>
                    </label>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="filter-actions">
                <button type="button" class="apply-filter-btn">Apply Filter</button>
                <button type="button" class="clear-filter-btn">Clear All</button>
            </div>
        </div>
        <?php
    }
}