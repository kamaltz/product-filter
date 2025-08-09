jQuery(document).ready(function ($) {
  // Filter accordion functionality
  $(".filter-title").on("click", function () {
    const $filterGroup = $(this).closest(".filter-group");
    const $panel = $filterGroup.find(".filter-panel");

    if ($filterGroup.hasClass("active")) {
      $filterGroup.removeClass("active");
      $panel.slideUp(300);
    } else {
      $filterGroup.addClass("active");
      $panel.slideDown(300);
    }
  });

  // Initialize filters that should be open by default
  $(".filter-group").each(function () {
    if ($(this).hasClass("active")) {
      $(this).find(".filter-panel").show();
    }
  });

  // Handle sort dropdown change
  $(".sort-dropdown").on("change", function () {
    const sortValue = $(this).val();
    // Update radio buttons if they exist
    $('input[name="sort_by"][value="' + sortValue + '"]')
      .prop("checked", true)
      .trigger("change");
    applyFilters();
  });

  // Handle sort radio button change
  $('input[name="sort_by"]').on("change", function () {
    const sortValue = $(this).val();
    $(".sort-dropdown").val(sortValue);
    
    // Update visual state for sort items
    $('.filter-sort-by').removeClass('active');
    $(this).closest('label').addClass('active');
    $(this).closest('.filter-item').addClass('filter-active').siblings().removeClass('filter-active');
    
    applyFilters();
  });

  // Handle all filter changes
  $(document).on("change", ".filter-item input", function () {
    applyFilters();
  });
  
  // Handle size swatch changes
  $(document).on("change", ".size-swatch input", function () {
    const $swatch = $(this).closest('.size-swatch');
    if ($(this).is(':checked')) {
      $swatch.addClass('active');
    } else {
      $swatch.removeClass('active');
    }
    applyFilters();
  });
  
  // Handle size swatch clicks (for better UX)
  $(document).on("click", ".size-swatch", function (e) {
    if (e.target.type !== 'checkbox') {
      const $checkbox = $(this).find('input[type="checkbox"]');
      $checkbox.prop('checked', !$checkbox.prop('checked')).trigger('change');
    }
  });
  
  // Handle product type checkbox visual feedback
  $(document).on("change", 'input[name="product_type[]"]', function () {
    const $item = $(this).closest('.filter-item');
    if ($(this).is(':checked')) {
      $item.addClass('filter-active');
    } else {
      $item.removeClass('filter-active');
    }
  });

  // Pagination functionality
  $(document).on("click", ".page-btn:not(.active):not(:disabled)", function () {
    const page = $(this).data("page");
    if (page) {
      loadProducts(page);

      $(".page-btn").removeClass("active");
      $(this).addClass("active");

      // Scroll to top of products
      $("html, body").animate(
        {
          scrollTop: $(".products-grid").offset().top - 100,
        },
        500
      );
    }
  });

  // Product hover image effect
  $(document).on("mouseenter", ".product-image img[data-hover]", function () {
    const $img = $(this);
    const hoverSrc = $img.data("hover");
    const originalSrc = $img.attr("src");

    $img.attr("data-original", originalSrc);
    $img.attr("src", hoverSrc);
  });

  $(document).on("mouseleave", ".product-image img[data-hover]", function () {
    const $img = $(this);
    const originalSrc = $img.data("original");

    if (originalSrc) {
      $img.attr("src", originalSrc);
    }
  });

  // Quick view functionality
  $(document).on("click", ".product-quick-view", function (e) {
    e.stopPropagation();
    const productId = $(this).data("product-id");
    openQuickView(productId);
  });

  // Add to cart functionality
  $(document).on("click", ".product-add-to-cart", function (e) {
    e.stopPropagation();
    const productId = $(this).data("product-id");
    addToCart(productId);
  });

  // Product item click handler
  $(document).on("click", ".product-item", function () {
    const productId = $(this).data("product-id");
    // Redirect to product page or handle as needed
    window.location.href = "/product/" + productId + "/";
  });

  function applyFilters(page = 1) {
    const filters = collectFilters();
    const perPage = $(".products-grid").data("per-page") || 12;

    // Show loading state
    $(".products-grid").addClass("loading");

    // AJAX request
    $.ajax({
      url: productFilter.ajax_url,
      type: "POST",
      data: {
        action: "filter_products",
        filters: filters,
        page: page,
        per_page: perPage,
        nonce: productFilter.nonce,
      },
      success: function (response) {
        if (response.success) {
          $(".products-grid").html(response.data.products);
          updatePagination(
            response.data.total_pages,
            response.data.current_page
          );
          updateProductCount(
            response.data.total_products,
            response.data.current_page,
            perPage
          );

          // Update URL without refreshing page
          updateURL(filters, page);
        } else {
          console.error("Filter error:", response.data);
          showError("Failed to load products. Please try again.");
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX error:", error);
        showError("Connection error. Please check your internet connection.");
      },
      complete: function () {
        $(".products-grid").removeClass("loading");
      },
    });
  }

  function collectFilters() {
    const filters = {};

    // Collect sort option
    const sortBy =
      $('input[name="sort_by"]:checked').val() || $(".sort-dropdown").val();
    if (sortBy) {
      filters.sort_by = sortBy;
    }

    // Collect category filters
    const categories = [];
    $('input[name="category[]"]:checked').each(function () {
      categories.push($(this).val());
    });
    if (categories.length > 0) {
      filters.categories = categories;
    }

    // Collect brand filters
    const brands = [];
    $('input[name="brand[]"]:checked').each(function () {
      brands.push($(this).val());
    });
    if (brands.length > 0) {
      filters.brands = brands;
    }

    // Collect product type filters
    const productTypes = [];
    $('input[name="product_type[]"]:checked').each(function () {
      productTypes.push($(this).val());
    });
    if (productTypes.length > 0) {
      filters.product_types = productTypes;
    }

    // Collect price range filters
    const priceRanges = [];
    $('input[name="price_range[]"]:checked').each(function () {
      priceRanges.push($(this).val());
    });
    if (priceRanges.length > 0) {
      filters.price_ranges = priceRanges;
    }

    // Collect size filters (including shoe sizes)
    const sizes = [];
    $('input[name="size[]"]:checked, input[name="shoe_size[]"]:checked').each(function () {
      sizes.push($(this).val());
    });
    if (sizes.length > 0) {
      filters.sizes = sizes;
    }
    
    // Collect shoe size filters specifically
    const shoeSizes = [];
    $('input[name="shoe_size[]"]:checked').each(function () {
      shoeSizes.push($(this).val());
    });
    if (shoeSizes.length > 0) {
      filters.shoe_sizes = shoeSizes;
    }

    // Collect color filters
    const colors = [];
    $('input[name="color[]"]:checked').each(function () {
      colors.push($(this).val());
    });
    if (colors.length > 0) {
      filters.colors = colors;
    }

    return filters;
  }

  function loadProducts(page = 1) {
    const filters = collectFilters();
    applyFilters(page);
  }

  function updatePagination(totalPages, currentPage) {
    const paginationContainer = $(".product-pagination");

    if (totalPages <= 1) {
      paginationContainer.hide();
      return;
    }

    let paginationHtml = "";

    // Previous button
    if (currentPage > 1) {
      paginationHtml += `<button class="page-btn page-prev" data-page="${
        currentPage - 1
      }">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none">
                    <path d="M7 1L1 7L7 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>`;
    }

    // Page numbers with ellipsis
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(totalPages, currentPage + 2);

    if (startPage > 1) {
      paginationHtml += `<button class="page-btn" data-page="1">1</button>`;
      if (startPage > 2) {
        paginationHtml += `<span class="page-dots">...</span>`;
      }
    }

    for (let i = startPage; i <= endPage; i++) {
      const activeClass = i === currentPage ? "active" : "";
      paginationHtml += `<button class="page-btn ${activeClass}" data-page="${i}">${i}</button>`;
    }

    if (endPage < totalPages) {
      if (endPage < totalPages - 1) {
        paginationHtml += `<span class="page-dots">...</span>`;
      }
      paginationHtml += `<button class="page-btn" data-page="${totalPages}">${totalPages}</button>`;
    }

    // Next button
    if (currentPage < totalPages) {
      paginationHtml += `<button class="page-btn page-next" data-page="${
        currentPage + 1
      }">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none">
                    <path d="M1 1L7 7L1 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>`;
    }

    paginationContainer.html(paginationHtml).show();
  }

  function updateProductCount(totalProducts, currentPage, perPage) {
    const $countElement = $(".products-count");
    if ($countElement.length) {
      const startItem = (currentPage - 1) * perPage + 1;
      const endItem = Math.min(currentPage * perPage, totalProducts);

      if (totalProducts > 0) {
        $countElement.text(
          `Showing ${startItem}–${endItem} of ${totalProducts} products`
        );
      } else {
        $countElement.text("No products found");
      }
    }
  }

  function updateURL(filters, page) {
    if (history.pushState) {
      const params = new URLSearchParams();

      // Add filters to URL
      Object.keys(filters).forEach((key) => {
        if (Array.isArray(filters[key])) {
          filters[key].forEach((value) => {
            params.append(key + "[]", value);
          });
        } else {
          params.set(key, filters[key]);
        }
      });

      // Add page if not 1
      if (page > 1) {
        params.set("page", page);
      }

      const newURL =
        window.location.pathname +
        (params.toString() ? "?" + params.toString() : "");
      history.pushState({ filters: filters, page: page }, "", newURL);
    }
  }

  function openQuickView(productId) {
    // Create modal or handle quick view
    console.log("Opening quick view for product:", productId);

    // Example implementation:
    // You can create a modal here or redirect to a quick view page
    // For now, we'll just log it

    // Optional: Open in a modal
    /*
        $.ajax({
            url: productFilter.ajax_url,
            type: 'POST',
            data: {
                action: 'get_product_quick_view',
                product_id: productId,
                nonce: productFilter.nonce
            },
            success: function(response) {
                if (response.success) {
                    showModal(response.data.html);
                }
            }
        });
        */
  }

  function addToCart(productId) {
    const $button = $(`.product-add-to-cart[data-product-id="${productId}"]`);
    const originalText = $button.text();

    $button.text("Adding...").prop("disabled", true);

    $.ajax({
      url: productFilter.ajax_url,
      type: "POST",
      data: {
        action: "add_to_cart",
        product_id: productId,
        nonce: productFilter.nonce,
      },
      success: function (response) {
        if (response.success) {
          $button.text("Added!");
          setTimeout(() => {
            $button.text(originalText).prop("disabled", false);
          }, 2000);

          // Update cart count if exists
          updateCartCount();

          // Show success notification
          showNotification("Product added to cart!", "success");
        } else {
          $button.text("Failed").addClass("error");
          setTimeout(() => {
            $button
              .text(originalText)
              .removeClass("error")
              .prop("disabled", false);
          }, 2000);

          showNotification("Failed to add product to cart.", "error");
        }
      },
      error: function () {
        $button.text("Error").addClass("error");
        setTimeout(() => {
          $button
            .text(originalText)
            .removeClass("error")
            .prop("disabled", false);
        }, 2000);

        showNotification("Connection error. Please try again.", "error");
      },
    });
  }

  function updateCartCount() {
    // Update cart count in header if exists
    $.ajax({
      url: productFilter.ajax_url,
      type: "POST",
      data: {
        action: "get_cart_count",
        nonce: productFilter.nonce,
      },
      success: function (response) {
        if (response.success) {
          $(".cart-count").text(response.data.count);
        }
      },
    });
  }

  function showNotification(message, type = "info") {
    // Create a simple notification system
    const notification = $(`
            <div class="product-notification ${type}" style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${
                  type === "success"
                    ? "#27ae60"
                    : type === "error"
                    ? "#e74c3c"
                    : "#3498db"
                };
                color: white;
                padding: 15px 20px;
                border-radius: 4px;
                z-index: 9999;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.3s ease;
            ">
                ${message}
            </div>
        `);

    $("body").append(notification);

    // Animate in
    setTimeout(() => {
      notification.css({
        opacity: 1,
        transform: "translateX(0)",
      });
    }, 100);

    // Animate out and remove
    setTimeout(() => {
      notification.css({
        opacity: 0,
        transform: "translateX(100%)",
      });
      setTimeout(() => notification.remove(), 300);
    }, 3000);
  }

  function showError(message) {
    $(".products-grid").html(`
            <div class="error-message" style="
                grid-column: 1 / -1;
                text-align: center;
                padding: 40px;
                color: #e74c3c;
                background: #fdf2f2;
                border: 1px solid #f5c6cb;
                border-radius: 4px;
            ">
                <p style="margin: 0; font-size: 16px;">${message}</p>
                <button onclick="location.reload()" style="
                    margin-top: 15px;
                    background: #e74c3c;
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 4px;
                    cursor: pointer;
                ">Retry</button>
            </div>
        `);
  }

  // Handle browser back/forward buttons
  window.addEventListener("popstate", function (event) {
    if (event.state && event.state.filters) {
      // Restore filter state
      $(".filter-item input").prop("checked", false);

      Object.keys(event.state.filters).forEach((key) => {
        const values = event.state.filters[key];
        if (Array.isArray(values)) {
          values.forEach((value) => {
            $(`input[name="${key}[]"][value="${value}"]`).prop("checked", true);
          });
        } else {
          $(`input[name="${key}"][value="${values}"]`).prop("checked", true);
          if (key === "sort_by") {
            $(".sort-dropdown").val(values);
          }
        }
      });

      applyFilters(event.state.page || 1);
    }
  });

  // Responsive grid adjustment
  function adjustGrid() {
    const container = $(".products-grid");
    const containerWidth = container.width();

    if (containerWidth < 480) {
      container.css("grid-template-columns", "1fr");
    } else if (containerWidth < 768) {
      container.css("grid-template-columns", "repeat(2, 1fr)");
    } else if (containerWidth < 1200) {
      container.css("grid-template-columns", "repeat(3, 1fr)");
    } else {
      container.css("grid-template-columns", "repeat(4, 1fr)");
    }
  }

  // Adjust grid on window resize
  $(window).on("resize", debounce(adjustGrid, 250));

  // Initial grid adjustment
  adjustGrid();

  // Initialize filters from URL on page load
  function initializeFromURL() {
    const urlParams = new URLSearchParams(window.location.search);

    urlParams.forEach((value, key) => {
      if (key.endsWith("[]")) {
        const baseName = key.replace("[]", "");
        $(`input[name="${baseName}[]"][value="${value}"]`).prop(
          "checked",
          true
        );
      } else if (key === "sort_by") {
        $(`input[name="sort_by"][value="${value}"]`).prop("checked", true);
        $(".sort-dropdown").val(value);
      } else {
        $(`input[name="${key}"][value="${value}"]`).prop("checked", true);
      }
    });

    // Apply filters if any were found in URL
    if (urlParams.toString()) {
      const page = urlParams.get("page") || 1;
      applyFilters(parseInt(page));
    }
  }

  // Debounce function for performance
  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  // Initialize visual states
  function initializeVisualStates() {
    // Set initial sort selection visual state
    $('input[name="sort_by_"]:checked').each(function() {
      $(this).closest('label').addClass('active');
      $(this).closest('.filter-item').addClass('filter-active');
    });
    
    // Set initial size swatch states
    $('.size-swatch input:checked').each(function() {
      $(this).closest('.size-swatch').addClass('active');
    });
    
    // Set initial product type checkbox states
    $('input[name="product_type[]"]:checked').each(function() {
      $(this).closest('.filter-item').addClass('filter-active');
    });
  }
  
  // Mobile filter toggle
  $(document).on('click', '.filter-toggle-btn', function() {
    $('.filter-custom.mobile-hidden').addClass('show');
    $('body').addClass('filter-open');
  });
  
  $(document).on('click', '.filter-close-btn', function() {
    $('.filter-custom.mobile-hidden').removeClass('show');
    $('body').removeClass('filter-open');
  });
  
  // Close filter on overlay click
  $(document).on('click', '.filter-custom.mobile-hidden', function(e) {
    if (e.target === this) {
      $(this).removeClass('show');
      $('body').removeClass('filter-open');
    }
  });
  
  // Initialize on page load
  initializeFromURL();
  initializeVisualStates();
});
