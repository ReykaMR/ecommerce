<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><i class="fas fa-heart me-2"></i>My Wishlist</h2>
            <a href="<?php echo site_url('home/products'); ?>" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Continue Shopping
            </a>
        </div>

        <?php if (empty($wishlist_items)): ?>
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-heart fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Your wishlist is empty</h4>
                    <p class="text-muted mb-4">Start adding products you love to your wishlist</p>
                    <a href="<?php echo site_url('home/products'); ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($wishlist_items as $item): ?>
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 product-card">
                            <?php
                            if (!isset($this->product_image_model)) {
                                $this->load->model('Product_image_model');
                            }
                            $primary_image = $this->Product_image_model->get_primary_image($item->product_id);
                            $image_url = $primary_image ? base_url($primary_image->image_url) : base_url('assets/images/placeholder.jpg');
                            ?>
                            <img src="<?php echo $image_url; ?>" class="card-img-top product-image"
                                alt="<?php echo htmlspecialchars($item->product_name); ?>"
                                onerror="this.src='<?php echo base_url('assets/images/placeholder.jpg'); ?>'">
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title"><?php echo htmlspecialchars($item->product_name); ?></h6>
                                <p class="card-text flex-grow-1 small text-muted">
                                    <?php echo character_limiter($item->description, 60); ?>
                                </p>
                                <div class="mt-auto">
                                    <p class="card-text fw-bold text-primary h6 mb-2">
                                        Rp <?php echo number_format($item->price, 0, ',', '.'); ?>
                                    </p>
                                    <div class="d-grid gap-1">
                                        <a href="<?php echo site_url('home/product/' . $item->product_id); ?>"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>Details
                                        </a>
                                        <button class="btn btn-outline-danger btn-sm remove-wishlist"
                                            data-product-id="<?php echo $item->product_id; ?>">
                                            <i class="fas fa-trash me-1"></i>Remove
                                        </button>
                                        <?php if ($item->stock > 0): ?>
                                            <button class="btn btn-primary btn-sm add-to-cart"
                                                data-product-id="<?php echo $item->product_id; ?>">
                                                <i class="fas fa-cart-plus me-1"></i>Add to Cart
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                <i class="fas fa-times me-1"></i>Out of Stock
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.remove-wishlist').on('click', function () {
            var productId = $(this).data('product-id');
            var button = $(this);

            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Removing...');

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
                        button.closest('.col-xl-3').fadeOut(300, function () {
                            $(this).remove();
                            if ($('.product-card').length === 0) {
                                location.reload();
                            }
                        });
                        updateWishlistCount(response.wishlist_count);
                    } else {
                        showAlert('danger', response.message);
                    }
                },
                error: function () {
                    showAlert('danger', 'An error occurred. Please try again.');
                },
                complete: function () {
                    button.prop('disabled', false).html('<i class="fas fa-trash me-1"></i>Remove');
                }
            });
        });
    });
</script>