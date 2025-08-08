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
            'show_sort_filter',
            [
                'label' => 'Show Sort By',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_gender_filter',
            [
                'label' => 'Show Shop For (Gender)',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_size_filter',
            [
                'label' => 'Show Size Filter',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_brand_filter',
            [
                'label' => 'Show Brand Filter',
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
        <div class="filter-custom">
            <div class="inner-filter">
                <div class="inner-filter--app">
                    
                    <?php if ($settings['show_sort_filter'] === 'yes'): ?>
                    <div class="filter-group">
                        <span class="filter-title filter-button filter-active">Sort by</span>
                        <div class="filter-panel">
                            <ul class="filter-size-group filter-list">
                                <li class="filter-item filter-active">
                                    <label for="Filter-manual" class="active-true filter-sort-by">
                                        <span class="box-check"></span>
                                        <input type="radio" name="sort_by_" value="manual" id="Filter-manual" checked>
                                        <span>Featured</span>
                                    </label>
                                </li>
                                <li class="filter-item">
                                    <label for="Filter-created-descending" class="filter-sort-by">
                                        <span class="box-check"></span>
                                        <input type="radio" name="sort_by_" value="created-descending" id="Filter-created-descending">
                                        <span>Newest first</span>
                                    </label>
                                </li>
                                <li class="filter-item">
                                    <label for="Filter-price-ascending" class="filter-sort-by">
                                        <span class="box-check"></span>
                                        <input type="radio" name="sort_by_" value="price-ascending" id="Filter-price-ascending">
                                        <span>Price: low to high</span>
                                    </label>
                                </li>
                                <li class="filter-item">
                                    <label for="Filter-price-descending" class="filter-sort-by">
                                        <span class="box-check"></span>
                                        <input type="radio" name="sort_by_" value="price-descending" id="Filter-price-descending">
                                        <span>Price: high to low</span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_gender_filter'] === 'yes'): ?>
                    <div class="filter-group">
                        <span class="filter-title filter-button filter-active">Shop For</span>
                        <div class="filter-panel">
                            <ul class="filter-size-group filter-list">
                                <li class="filter-item">
                                    <label for="Filter-women">
                                        <input type="checkbox" name="filter.p.product_type" value="Women" id="Filter-women">
                                        <span class="box-check"></span>
                                        <span>Women</span>
                                    </label>
                                </li>
                                <li class="filter-item">
                                    <label for="Filter-men">
                                        <input type="checkbox" name="filter.p.product_type" value="Men" id="Filter-men">
                                        <span class="box-check"></span>
                                        <span>Men</span>
                                    </label>
                                </li>
                                <li class="filter-item">
                                    <label for="Filter-unisex">
                                        <input type="checkbox" name="filter.p.product_type" value="Unisex" id="Filter-unisex">
                                        <span class="box-check"></span>
                                        <span>Unisex</span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_size_filter'] === 'yes'): ?>
                    <div class="filter-group size-footwear-women-true">
                        <span class="filter-title filter-button filter-active">US Women's shoe size</span>
                        <div class="filter-panel">
                            <ul class="filter-size-group filter-list ukuran-sepatu">
                                <?php for ($i = 5; $i <= 10; $i += 0.5): ?>
                                <li class="filter-item" data-sort="US <?php echo $i; ?>">
                                    <label for="Filter-size-women-<?php echo str_replace('.', '-', $i); ?>">
                                        <input type="checkbox" name="filter.p.tag" value="size-US <?php echo $i; ?>-women-footwear" id="Filter-size-women-<?php echo str_replace('.', '-', $i); ?>">
                                        <span>US <?php echo $i; ?></span>
                                    </label>
                                </li>
                                <?php endfor; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="filter-group size-footwear-men-true">
                        <span class="filter-title filter-button filter-active">US Men's shoe size</span>
                        <div class="filter-panel">
                            <ul class="filter-size-group filter-list ukuran-sepatu">
                                <?php for ($i = 7; $i <= 13; $i += 0.5): ?>
                                <li class="filter-item" data-sort="US <?php echo $i; ?>">
                                    <label for="Filter-size-men-<?php echo str_replace('.', '-', $i); ?>">
                                        <input type="checkbox" name="filter.p.tag" value="size-US <?php echo $i; ?>-men-footwear" id="Filter-size-men-<?php echo str_replace('.', '-', $i); ?>">
                                        <span>US <?php echo $i; ?></span>
                                    </label>
                                </li>
                                <?php endfor; ?>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_brand_filter'] === 'yes'): ?>
                    <div class="filter-group">
                        <span class="filter-title filter-button filter-active">Brand</span>
                        <div class="filter-panel">
                            <ul class="filter-size-group filter-list">
                                <li class="filter-item">
                                    <label for="Filter-brand-on">
                                        <input type="checkbox" name="filter.p.vendor" value="On" id="Filter-brand-on">
                                        <span class="box-check"></span>
                                        <span>On</span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
        <?php
    }
}