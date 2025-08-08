jQuery(document).ready(function($) {
    
    // Filter functionality
    $('.apply-filter-btn').on('click', function() {
        applyFilters();
    });
    
    $('.clear-filter-btn').on('click', function() {
        clearFilters();
    });
    
    // Pagination functionality
    $(document).on('click', '.page-btn', function() {
        const page = $(this).data('page');
        loadProducts(page);
        
        $('.page-btn').removeClass('active');
        $(this).addClass('active');
    });
    
    // Handle filter option clicks
    $('.filter-option').on('click', function() {
        const checkbox = $(this).find('input[type="checkbox"]');
        checkbox.prop('checked', !checkbox.prop('checked'));
        $(this).toggleClass('active', checkbox.prop('checked'));
        
        // Auto-apply filters
        applyFilters();
    });
    
    // Initialize active states
    $('.filter-option input[type="checkbox"]:checked').each(function() {
        $(this).closest('.filter-option').addClass('active');
    });
    
    function applyFilters(page = 1) {
        const filters = {};
        const perPage = $('.products-grid').data('per-page') || 12;
        
        // Collect category filters
        const categories = [];
        $('input[name="category[]"]:checked').each(function() {
            categories.push($(this).val());
        });
        if (categories.length > 0) {
            filters.product_cat = categories;
        }
        
        // Collect product type filters
        const productTypes = [];
        $('input[name="product_type[]"]:checked').each(function() {
            productTypes.push($(this).val());
        });
        if (productTypes.length > 0) {
            filters.product_type = productTypes;
        }
        
        // Collect price range filters
        const priceRanges = [];
        $('input[name="price_range[]"]:checked').each(function() {
            priceRanges.push($(this).val());
        });
        if (priceRanges.length > 0) {
            filters.price_range = priceRanges;
        }
        
        // Show loading state
        $('.products-grid').addClass('loading');
        
        // AJAX request
        $.ajax({
            url: productFilter.ajax_url,
            type: 'POST',
            data: {
                action: 'filter_products',
                filters: filters,
                page: page,
                per_page: perPage,
                nonce: productFilter.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('.products-grid').html(response.data.products);
                    updatePagination(response.data.total_pages, response.data.current_page);
                } else {
                    console.error('Filter error:', response.data);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
            },
            complete: function() {
                $('.products-grid').removeClass('loading');
            }
        });
    }
    
    function clearFilters() {
        $('.filter-option input[type="checkbox"]').prop('checked', false);
        $('.filter-option').removeClass('active');
        loadProducts(1);
    }
    
    function loadProducts(page = 1) {
        const perPage = $('.products-grid').data('per-page') || 12;
        
        $('.products-grid').addClass('loading');
        
        $.ajax({
            url: productFilter.ajax_url,
            type: 'POST',
            data: {
                action: 'filter_products',
                filters: {},
                page: page,
                per_page: perPage,
                nonce: productFilter.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('.products-grid').html(response.data.products);
                    updatePagination(response.data.total_pages, response.data.current_page);
                }
            },
            complete: function() {
                $('.products-grid').removeClass('loading');
            }
        });
    }
    
    function updatePagination(totalPages, currentPage) {
        const paginationContainer = $('.product-pagination');
        
        if (totalPages <= 1) {
            paginationContainer.hide();
            return;
        }
        
        let paginationHtml = '';
        
        for (let i = 1; i <= totalPages; i++) {
            const activeClass = (i === currentPage) ? 'active' : '';
            paginationHtml += `<button class="page-btn ${activeClass}" data-page="${i}">${i}</button>`;
        }
        
        paginationContainer.html(paginationHtml).show();
    }
    
    // Product item click handler (optional)
    $(document).on('click', '.product-item', function() {
        // Add your product click logic here
        // For example, redirect to product page or open modal
        console.log('Product clicked:', $(this).find('.product-title').text());
    });
    
    // Responsive grid adjustment
    function adjustGrid() {
        const container = $('.products-grid');
        const containerWidth = container.width();
        
        if (containerWidth < 480) {
            container.css('grid-template-columns', '1fr');
        } else if (containerWidth < 768) {
            container.css('grid-template-columns', 'repeat(2, 1fr)');
        } else if (containerWidth < 1200) {
            container.css('grid-template-columns', 'repeat(3, 1fr)');
        } else {
            container.css('grid-template-columns', 'repeat(4, 1fr)');
        }
    }
    
    // Adjust grid on window resize
    $(window).on('resize', function() {
        adjustGrid();
    });
    
    // Initial grid adjustment
    adjustGrid();
});