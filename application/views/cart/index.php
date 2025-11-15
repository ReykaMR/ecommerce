<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><i class="fas fa-shopping-cart me-2"></i>Shopping Cart</h2>
            <a href="<?php echo site_url('home/products'); ?>" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Continue Shopping
            </a>
        </div>

        <?php if (empty($cart_items)): ?>
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Your cart is empty</h4>
                    <p class="text-muted mb-4">Start shopping to add items to your cart</p>
                    <a href="<?php echo site_url('home/products'); ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Cart Items (<?php echo count($cart_items); ?>)</h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($cart_items as $item): ?>
                                <div class="row align-items-center mb-4 pb-4 border-bottom">
                                    <div class="col-md-2">
                                        <?php
                                        $primary_image = $this->Product_image_model->get_primary_image($item->product_id);
                                        $image_url = $primary_image ? base_url($primary_image->image_url) : base_url('assets/images/placeholder.jpg');
                                        ?>
                                        <img src="<?php echo $image_url; ?>" class="img-fluid rounded"
                                            alt="<?php echo $item->product_name; ?>"
                                            onerror="this.src='<?php echo base_url('assets/images/placeholder.jpg'); ?>'"
                                            style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="mb-1"><?php echo $item->product_name; ?></h6>
                                        <p class="text-muted small mb-0">Stock: <?php echo $item->stock; ?></p>
                                    </div>
                                    <div class="col-md-2">
                                        <p class="mb-0 fw-bold text-primary">
                                            Rp <?php echo number_format($item->price, 0, ',', '.'); ?>
                                        </p>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control form-control-sm quantity-input"
                                            value="<?php echo $item->quantity; ?>" min="1" max="<?php echo $item->stock; ?>"
                                            data-cart-id="<?php echo $item->cart_id; ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <p class="mb-1 fw-bold">
                                            Rp <?php echo number_format($item->price * $item->quantity, 0, ',', '.'); ?>
                                        </p>
                                        <button class="btn btn-danger btn-sm remove-item text-white border border-0"
                                            data-cart-id="<?php echo $item->cart_id; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-light text-dark">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>Rp <?php echo number_format($cart_total, 0, ',', '.'); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>Rp 10.000</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span>Rp 0</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total:</strong>
                                <strong class="h5 text-primary">
                                    Rp <?php echo number_format($cart_total + 10000, 0, ',', '.'); ?>
                                </strong>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="<?php echo site_url('checkout'); ?>" class="btn btn-primary btn-lg">
                                    <i class="fas fa-credit-card me-2"></i>Proceed to Checkout
                                </a>
                                <a href="<?php echo site_url('cart/clear'); ?>" class="btn btn-outline-danger"
                                    onclick="return confirm('Are you sure you want to clear your cart?')">
                                    <i class="fas fa-trash me-2"></i>Clear Cart
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Update quantity
        $('.quantity-input').on('change', function () {
            var cartId = $(this).data('cart-id');
            var quantity = $(this).val();

            if (quantity < 1) {
                $(this).val(1);
                return;
            }

            $.ajax({
                url: '<?php echo site_url('cart/update'); ?>',
                method: 'POST',
                data: {
                    cart_id: cartId,
                    quantity: quantity
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert('danger', response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                },
                error: function () {
                    showAlert('danger', 'An error occurred. Please try again.');
                }
            });
        });

        // Remove item
        $('.remove-item').on('click', function () {
            var cartId = $(this).data('cart-id');
            if (confirm('Are you sure you want to remove this item from your cart?')) {
                showAlert('info', 'Removing item from cart...');
                window.location.href = '<?php echo site_url('cart/remove/'); ?>' + cartId;
            }
        });
    });
</script>