<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo site_url('cart'); ?>">Cart</a></li>
                <li class="breadcrumb-item active">Checkout</li>
            </ol>
        </nav>

        <h2 class="fw-bold mb-4"><i class="fas fa-cash-register me-2"></i>Checkout</h2>

        <!-- Alert Container for Checkout -->
        <div id="checkoutAlerts"></div>
    </div>
</div>

<div class="row">
    <!-- Checkout Form -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-light text-dark">
                <h5 class="mb-0">Shipping Information</h5>
            </div>
            <div class="card-body">
                <?php echo form_open('checkout/process'); ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="full_name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="full_name" name="full_name"
                            value="<?php echo $user->full_name; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo $user->email; ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number *</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $user->phone; ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label for="shipping_address" class="form-label">Shipping Address *</label>
                    <textarea class="form-control" id="shipping_address" name="shipping_address" rows="4" required
                        placeholder="Enter your complete shipping address"><?php echo set_value('shipping_address'); ?></textarea>
                    <?php echo form_error('shipping_address', '<div class="text-danger small mt-1">', '</div>'); ?>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Payment Method *</label>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer"
                            value="bank_transfer" checked>
                        <label class="form-check-label" for="bank_transfer">
                            <i class="fas fa-university me-2"></i>Bank Transfer
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" id="credit_card"
                            value="credit_card">
                        <label class="form-check-label" for="credit_card">
                            <i class="fas fa-credit-card me-2"></i>Credit Card
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod">
                        <label class="form-check-label" for="cod">
                            <i class="fas fa-money-bill-wave me-2"></i>Cash on Delivery
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Order Notes (Optional)</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"
                        placeholder="Any special instructions for your order..."><?php echo set_value('notes'); ?></textarea>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-shipping-fast me-2"></i>Place Order
                    </button>
                </div>
                <?php echo form_close(); ?>
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
                <!-- Cart Items -->
                <?php foreach ($cart_items as $item): ?>
                    <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
                        <div class="flex-grow-1">
                            <h6 class="mb-1"><?php echo $item->product_name; ?></h6>
                            <p class="text-muted small mb-0">Qty: <?php echo $item->quantity; ?></p>
                            <p class="text-muted small mb-0">Rp <?php echo number_format($item->price, 0, ',', '.'); ?> each
                            </p>
                        </div>
                        <div class="text-end">
                            <p class="mb-0 fw-bold">
                                Rp <?php echo number_format($item->price * $item->quantity, 0, ',', '.'); ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Totals -->
                <div class="mt-4">
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
                        <strong class="h5 text-success">
                            Rp <?php echo number_format($cart_total + 10000, 0, ',', '.'); ?>
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>