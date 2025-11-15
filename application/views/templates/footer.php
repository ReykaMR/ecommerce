</div>
</main>

<!-- Footer -->
<footer class="footer mt-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3">
                <h5><i class="fas fa-shopping-bag me-2"></i>Ecommerce</h5>
                <p>Your trusted online shopping destination. Quality products at affordable prices.</p>
                <div class="social-links">
                    <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo site_url('home'); ?>" class="text-light text-decoration-none">Home</a></li>
                    <li><a href="<?php echo site_url('home/products'); ?>"
                            class="text-light text-decoration-none">Products</a></li>
                    <li><a href="#" class="text-light text-decoration-none">About Us</a></li>
                    <li><a href="#" class="text-light text-decoration-none">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-3">
                <h5>Customer Service</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-light text-decoration-none">Shipping Info</a></li>
                    <li><a href="#" class="text-light text-decoration-none">Returns</a></li>
                    <li><a href="#" class="text-light text-decoration-none">Privacy Policy</a></li>
                    <li><a href="#" class="text-light text-decoration-none">Terms & Conditions</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-3">
                <h5>Contact Info</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-map-marker-alt me-2"></i> 123 Street, City</li>
                    <li><i class="fas fa-phone me-2"></i> +1 234 567 890</li>
                    <li><i class="fas fa-envelope me-2"></i> info@ecommerce.com</li>
                </ul>
            </div>
        </div>
        <hr class="bg-light">
        <div class="text-center">
            <p>&copy; <?php echo date('Y'); ?> Ecommerce. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Global Alert System
    class AlertSystem {
        constructor() {
            this.alertContainer = $('#alertContainer');
            this.alertQueue = [];
            this.isShowingAlert = false;

            this.processFlashMessages();
        }

        // Process PHP flash messages
        processFlashMessages() {
            <?php if ($this->session->flashdata('success')): ?>
                this.showAlert('success', '<?php echo addslashes($this->session->flashdata('success')); ?>');
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                this.showAlert('danger', '<?php echo addslashes($this->session->flashdata('error')); ?>');
            <?php endif; ?>
        }

        // Show alert (add to queue)
        showAlert(type, message, duration = 5000) {
            this.alertQueue.push({ type, message, duration });
            this.processQueue();
        }

        // Process alert queue
        processQueue() {
            if (this.isShowingAlert || this.alertQueue.length === 0) {
                return;
            }

            this.isShowingAlert = true;
            const alert = this.alertQueue.shift();
            this.displayAlert(alert.type, alert.message, alert.duration);
        }

        // Display individual alert
        displayAlert(type, message, duration) {
            const alertClass = type === 'success' ? 'alert-success' :
                type === 'warning' ? 'alert-warning' :
                    type === 'info' ? 'alert-info' : 'alert-danger';

            const icon = type === 'success' ? 'fa-check-circle' :
                type === 'warning' ? 'fa-exclamation-triangle' :
                    type === 'info' ? 'fa-info-circle' : 'fa-exclamation-circle';

            const alertId = 'alert-' + Date.now();
            const alertHtml = `
                    <div id="${alertId}" class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        <i class="fas ${icon} me-2"></i>${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

            this.alertContainer.append(alertHtml);

            setTimeout(() => {
                $(`#${alertId}`).alert('close');
            }, duration);

            $(`#${alertId}`).on('closed.bs.alert', () => {
                setTimeout(() => {
                    this.isShowingAlert = false;
                    this.processQueue();
                }, 300);
            });
        }

        clearAll() {
            this.alertQueue = [];
            this.alertContainer.empty();
            this.isShowingAlert = false;
        }
    }

    let alertSystem;

    $(document).ready(function () {
        alertSystem = new AlertSystem();

        // Global function untuk memanggil alert dari mana saja
        window.showAlert = function (type, message, duration = 5000) {
            alertSystem.showAlert(type, message, duration);
        };

        // Global function untuk clear semua alert
        window.clearAlerts = function () {
            alertSystem.clearAll();
        };

        // Initialize cart and wishlist counts on page load
        initializeCounts();

        // Initialize add to cart functionality
        initializeAddToCart();

        // Initialize wishlist functionality
        initializeAddToWishlist();

        // Check initial wishlist status for products on page
        checkInitialWishlistStatus();
    });

    // Initialize cart and wishlist counts
    function initializeCounts() {
        $.ajax({
            url: '<?php echo site_url('cart/count'); ?>',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                updateCartCount(response.count);
            },
            error: function (xhr, status, error) {
                console.log('Cart count not available (user may not be logged in)');
                updateCartCount(0);
            }
        });

        $.ajax({
            url: '<?php echo site_url('wishlist/count'); ?>',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                updateWishlistCount(response.count);
            },
            error: function (xhr, status, error) {
                console.log('Wishlist count not available (user may not be logged in)');
                updateWishlistCount(0);
            }
        });
    }

    // Function to initialize add to cart buttons
    function initializeAddToCart() {
        $(document).on('click', '.add-to-cart', function (e) {
            e.preventDefault();
            var productId = $(this).data('product-id');
            var quantity = $(this).data('quantity') || 1;
            var button = $(this);

            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Adding...');

            $.ajax({
                url: '<?php echo site_url('cart/add'); ?>',
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        updateCartCount(response.cart_count);
                    } else {
                        showAlert('danger', response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    showAlert('danger', 'An error occurred. Please try again.');
                },
                complete: function () {
                    button.prop('disabled', false).html('<i class="fas fa-cart-plus me-1"></i>Add to Cart');
                }
            });
        });
    }

    // Function to update cart count in navbar
    function updateCartCount(count) {
        let cartBadge = $('.cart-badge');
        let cartLink = $('.fa-shopping-cart').parent();

        if (count > 0) {
            if (cartBadge.length === 0) {
                cartLink.append('<span class="badge bg-danger cart-badge">' + count + '</span>');
            } else {
                cartBadge.text(count);
            }
        } else {
            cartBadge.remove();
        }
    }

    // Function to initialize add to wishlist buttons
    function initializeAddToWishlist() {
        $(document).on('click', '.add-to-wishlist', function (e) {
            e.preventDefault();
            var productId = $(this).data('product-id');
            var button = $(this);

            button.prop('disabled', true);
            var originalHtml = button.html();
            button.html('<i class="fas fa-spinner fa-spin me-1"></i>...');

            $.ajax({
                url: '<?php echo site_url('wishlist/add'); ?>',
                method: 'POST',
                data: {
                    product_id: productId
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        updateWishlistCount(response.wishlist_count);
                        button.removeClass('btn-outline-primary').addClass('btn-primary')
                            .html('<i class="fas fa-heart me-1"></i>In Wishlist')
                            .off('click');

                        button.addClass('remove-from-wishlist').removeClass('add-to-wishlist')
                            .on('click', removeFromWishlistHandler);
                    } else {
                        showAlert('info', response.message);
                        button.removeClass('btn-outline-primary').addClass('btn-primary')
                            .html('<i class="fas fa-heart me-1"></i>In Wishlist')
                            .off('click')
                            .addClass('remove-from-wishlist').removeClass('add-to-wishlist')
                            .on('click', removeFromWishlistHandler);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    showAlert('danger', 'An error occurred. Please try again.');
                    button.html(originalHtml);
                },
                complete: function () {
                    button.prop('disabled', false);
                }
            });
        });
    }

    // Handler for remove from wishlist
    function removeFromWishlistHandler(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        var button = $(this);

        button.prop('disabled', true);
        var originalHtml = button.html();
        button.html('<i class="fas fa-spinner fa-spin me-1"></i>...');

        $.ajax({
            url: '<?php echo site_url('wishlist/remove'); ?>',
            method: 'POST',
            data: {
                product_id: productId
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    showAlert('success', response.message);
                    updateWishlistCount(response.wishlist_count);
                    button.removeClass('btn-danger').addClass('btn-outline-danger')
                        .html('<i class="fas fa-heart me-1"></i>Add to Wishlist')
                        .off('click')
                        .addClass('add-to-wishlist').removeClass('remove-from-wishlist')
                        .on('click', function (e) {
                            initializeAddToWishlist();
                            $(this).trigger('click');
                        });
                } else {
                    showAlert('danger', response.message);
                    button.html(originalHtml);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', status, error);
                showAlert('danger', 'An error occurred. Please try again.');
                button.html(originalHtml);
            },
            complete: function () {
                button.prop('disabled', false);
            }
        });
    }

    // Check initial wishlist status for products on page
    function checkInitialWishlistStatus() {
        <?php if ($this->session->userdata('logged_in')): ?>

            var productIds = [];
            $('.add-to-wishlist').each(function () {
                var productId = $(this).data('product-id');
                if (productId) {
                    productIds.push(productId);
                }
            });

            if (productIds.length > 0) {
                $.ajax({
                    url: '<?php echo site_url('wishlist/check_status'); ?>',
                    method: 'POST',
                    data: {
                        product_ids: productIds
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $.each(response.in_wishlist, function (index, productId) {
                                var button = $('.add-to-wishlist[data-product-id="' + productId + '"]');
                                if (button.length > 0) {
                                    button.removeClass('btn-outline-primary').addClass('btn-primary')
                                        .html('<i class="fas fa-heart me-1"></i>In Wishlist')
                                        .off('click')
                                        .addClass('remove-from-wishlist').removeClass('add-to-wishlist')
                                        .on('click', removeFromWishlistHandler);
                                }
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log('Wishlist status check failed (user may not be logged in)');
                    }
                });
            }
        <?php endif; ?>
    }

    // Function to update wishlist count in navbar
    function updateWishlistCount(count) {
        let wishlistBadge = $('.wishlist-badge');
        let wishlistLink = $('.fa-heart').parent();

        if (count > 0) {
            if (wishlistBadge.length === 0) {
                wishlistLink.append('<span class="badge bg-danger wishlist-badge">' + count + '</span>');
            } else {
                wishlistBadge.text(count);
            }
        } else {
            wishlistBadge.remove();
        }
    }

    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (mutation.addedNodes && mutation.addedNodes.length > 0) {
                $(mutation.addedNodes).find('.add-to-cart').each(function () {
                    // Elements are automatically handled by event delegation
                });
                $(mutation.addedNodes).find('.add-to-wishlist').each(function () {
                    checkInitialWishlistStatus();
                });
            }
        });
    });

    // Start observing
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
</script>
</body>

</html>