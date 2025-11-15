<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Order #<?php echo $order->order_number; ?></h2>
    <a href="<?php echo site_url('admin/orders'); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Orders
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Order Items -->
        <div class="card mb-4">
            <div class="card-header bg-light text-dark">
                <h5 class="mb-0">Order Items</h5>
            </div>
            <div class="card-body">
                <?php foreach ($order_items as $item): ?>
                    <div class="row align-items-center mb-3 pb-3 border-bottom">
                        <div class="col-md-2">
                            <?php
                            // Load the model if not already loaded
                            if (!isset($this->product_image_model)) {
                                $this->load->model('Product_image_model');
                            }
                            $primary_image = $this->Product_image_model->get_primary_image($item->product_id);
                            $image_url = $primary_image ? base_url($primary_image->image_url) : base_url('assets/images/placeholder.jpg');
                            ?>
                            <img src="<?php echo $image_url; ?>" class="img-fluid rounded"
                                alt="<?php echo htmlspecialchars($item->product_name); ?>"
                                onerror="this.src='<?php echo base_url('assets/images/placeholder.jpg'); ?>'"
                                style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-1"><?php echo $item->product_name; ?></h6>
                            <p class="text-muted small mb-0">Quantity: <?php echo $item->quantity; ?></p>
                        </div>
                        <div class="col-md-4 text-end">
                            <p class="mb-1 fw-bold text-primary">
                                Rp <?php echo number_format($item->unit_price, 0, ',', '.'); ?> each
                            </p>
                            <p class="mb-0 fw-bold">
                                Rp <?php echo number_format($item->subtotal, 0, ',', '.'); ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Order Totals -->
                <div class="row mt-4">
                    <div class="col-md-6 offset-md-6">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>Rp
                                <?php echo number_format($order->total_amount - $order->shipping_cost, 0, ',', '.'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>Rp <?php echo number_format($order->shipping_cost, 0, ',', '.'); ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Total:</strong>
                            <strong class="h5 text-primary">
                                Rp <?php echo number_format($order->total_amount, 0, ',', '.'); ?>
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="card">
            <div class="card-header bg-light text-dark">
                <h5 class="mb-0">Shipping Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Customer:</strong> <?php echo $order->full_name; ?></p>
                <p><strong>Email:</strong> <?php echo $order->email; ?></p>
                <p><strong>Phone:</strong> <?php echo $order->phone; ?></p>
                <p><strong>Address:</strong><br><?php echo nl2br($order->shipping_address); ?></p>
                <?php if ($order->notes): ?>
                    <p><strong>Order Notes:</strong><br><?php echo nl2br($order->notes); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Order Status -->
        <div class="card mb-4">
            <div class="card-header bg-light text-dark">
                <h5 class="mb-0">Order Status</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo site_url('admin/orders/update_status/' . $order->order_id); ?>" method="post">
                    <div class="mb-3">
                        <label for="status" class="form-label">Current Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" <?php echo $order->status == 'pending' ? 'selected' : ''; ?>>Pending
                            </option>
                            <option value="paid" <?php echo $order->status == 'paid' ? 'selected' : ''; ?>>Paid</option>
                            <option value="processing" <?php echo $order->status == 'processing' ? 'selected' : ''; ?>>
                                Processing</option>
                            <option value="shipped" <?php echo $order->status == 'shipped' ? 'selected' : ''; ?>>Shipped
                            </option>
                            <option value="delivered" <?php echo $order->status == 'delivered' ? 'selected' : ''; ?>>
                                Delivered</option>
                            <option value="cancelled" <?php echo $order->status == 'cancelled' ? 'selected' : ''; ?>>
                                Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="card">
            <div class="card-header bg-light text-dark">
                <h5 class="mb-0">Payment Information</h5>
            </div>
            <div class="card-body">
                <?php if ($payment): ?>
                    <p><strong>Method:</strong> <?php echo ucfirst(str_replace('_', ' ', $payment->payment_method)); ?></p>
                    <p><strong>Amount:</strong> Rp <?php echo number_format($payment->amount, 0, ',', '.'); ?></p>
                    <p><strong>Status:</strong>
                        <span class="badge 
                            <?php
                            $payment_status_badge = [
                                'pending' => 'bg-warning',
                                'success' => 'bg-success',
                                'failed' => 'bg-danger'
                            ];
                            echo $payment_status_badge[$payment->status];
                            ?>">
                            <?php echo ucfirst($payment->status); ?>
                        </span>
                    </p>

                    <?php if ($payment->payment_proof): ?>
                        <hr>
                        <h6 class="fw-bold">Payment Proof</h6>
                        <a href="<?php echo base_url($payment->payment_proof); ?>" target="_blank"
                            class="btn btn-sm btn-outline-primary w-100 mb-2">
                            <i class="fas fa-eye me-2"></i>View Proof
                        </a>
                    <?php endif; ?>

                    <?php if ($payment->status == 'pending'): ?>
                        <hr>
                        <div class="d-grid gap-2">
                            <a href="<?php echo site_url('admin/payments/confirm/' . $payment->payment_id); ?>"
                                class="btn btn-success btn-sm" onclick="return confirm('Mark this payment as success?')">
                                <i class="fas fa-check me-2"></i>Confirm Payment
                            </a>
                            <a href="<?php echo site_url('admin/payments/reject/' . $payment->payment_id); ?>"
                                class="btn btn-danger btn-sm" onclick="return confirm('Mark this payment as failed?')">
                                <i class="fas fa-times me-2"></i>Reject Payment
                            </a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-muted text-center mb-0">No payment information available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>