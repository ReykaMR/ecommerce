<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo site_url('orders'); ?>">My Orders</a></li>
                <li class="breadcrumb-item active">Order #<?php echo $order->order_number; ?></li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Order #<?php echo $order->order_number; ?></h2>
            <span class="badge 
                <?php
                $status_badge = [
                    'pending' => 'bg-warning',
                    'paid' => 'bg-info',
                    'processing' => 'bg-primary',
                    'shipped' => 'bg-secondary',
                    'delivered' => 'bg-success',
                    'cancelled' => 'bg-danger'
                ];
                echo $status_badge[$order->status];
                ?> fs-6">
                <?php echo ucfirst($order->status); ?>
            </span>
        </div>

        <div class="row">
            <!-- Order Details -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">Order Items</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($order_items as $item): ?>
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
                <div class="card mt-4">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">Shipping Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Address:</strong><br><?php echo nl2br($order->shipping_address); ?></p>
                        <?php if ($order->notes): ?>
                            <p><strong>Order Notes:</strong><br><?php echo nl2br($order->notes); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Order Actions & Payment -->
            <div class="col-lg-4">
                <!-- Order Actions -->
                <div class="card mb-4">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">Order Actions</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($order->status == 'pending'): ?>
                            <div class="d-grid gap-2">
                                <a href="<?php echo site_url('orders/cancel/' . $order->order_id); ?>"
                                    class="btn btn-danger text-white border border-0"
                                    onclick="return confirm('Are you sure you want to cancel this order?')">
                                    <i class="fas fa-times me-2"></i>Cancel Order
                                </a>
                            </div>
                        <?php elseif ($order->status == 'delivered'): ?>
                            <button class="btn btn-success w-100">
                                <i class="fas fa-check me-2"></i>Order Completed
                            </button>
                        <?php else: ?>
                            <p class="text-muted text-center mb-0">No actions available</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="card">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">Payment Information</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($payment): ?>
                            <p><strong>Method:</strong>
                                <?php echo ucfirst(str_replace('_', ' ', $payment->payment_method)); ?></p>
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

                            <?php if ($payment->status == 'pending' && $payment->payment_method != 'cod'): ?>
                                <hr>
                                <h6 class="fw-bold">Upload Payment Proof</h6>
                                <?php echo form_open_multipart('orders/upload_payment_proof/' . $order->order_id); ?>
                                <div class="mb-3">
                                    <input type="file" class="form-control" name="payment_proof" accept="image/*,.pdf" required>
                                    <div class="form-text">Upload proof of payment (JPG, PNG, PDF, max 2MB)</div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-upload me-2"></i>Upload Proof
                                </button>
                                <?php echo form_close(); ?>
                            <?php endif; ?>

                            <?php if ($payment->payment_proof): ?>
                                <hr>
                                <h6 class="fw-bold">Payment Proof</h6>
                                <a href="<?php echo base_url($payment->payment_proof); ?>" target="_blank"
                                    class="btn btn-sm btn-outline-primary w-100">
                                    <i class="fas fa-eye me-2"></i>View Proof
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-muted text-center mb-0">No payment information available</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="card mt-4">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">Order Timeline</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div
                                class="timeline-item <?php echo in_array($order->status, ['pending', 'paid', 'processing', 'shipped', 'delivered']) ? 'completed' : ''; ?>">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Order Placed</h6>
                                    <small><?php echo date('M d, Y H:i', strtotime($order->created_at)); ?></small>
                                </div>
                            </div>
                            <div
                                class="timeline-item <?php echo in_array($order->status, ['paid', 'processing', 'shipped', 'delivered']) ? 'completed' : ''; ?>">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Payment Confirmed</h6>
                                    <small>
                                        <?php if ($payment && $payment->paid_at): ?>
                                            <?php echo date('M d, Y H:i', strtotime($payment->paid_at)); ?>
                                        <?php else: ?>
                                            Pending
                                        <?php endif; ?>
                                    </small>
                                </div>
                            </div>
                            <div
                                class="timeline-item <?php echo in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'completed' : ''; ?>">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Processing</h6>
                                    <small>Preparing your order</small>
                                </div>
                            </div>
                            <div
                                class="timeline-item <?php echo in_array($order->status, ['shipped', 'delivered']) ? 'completed' : ''; ?>">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Shipped</h6>
                                    <small>On the way</small>
                                </div>
                            </div>
                            <div class="timeline-item <?php echo $order->status == 'delivered' ? 'completed' : ''; ?>">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Delivered</h6>
                                    <small>Order completed</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }

    .timeline-item.completed .timeline-marker {
        background-color: #28a745;
        border-color: #28a745;
    }

    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 0;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #dee2e6;
        background-color: #fff;
    }

    .timeline-content h6 {
        margin-bottom: 5px;
        font-size: 0.9rem;
    }

    .timeline-content small {
        color: #6c757d;
    }
</style>