<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><i class="fas fa-receipt me-2"></i>My Orders</h2>
            <a href="<?php echo site_url('home/products'); ?>" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Continue Shopping
            </a>
        </div>

        <?php if (empty($orders)): ?>
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No orders yet</h4>
                    <p class="text-muted mb-4">Start shopping to see your orders here</p>
                    <a href="<?php echo site_url('home/products'); ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $order->order_number; ?></strong>
                                        </td>
                                        <td>
                                            <?php echo date('M d, Y', strtotime($order->created_at)); ?>
                                        </td>
                                        <td>
                                            <strong>Rp <?php echo number_format($order->total_amount, 0, ',', '.'); ?></strong>
                                        </td>
                                        <td>
                                            <?php
                                            $status_badge = [
                                                'pending' => 'bg-warning',
                                                'paid' => 'bg-info',
                                                'processing' => 'bg-primary',
                                                'shipped' => 'bg-secondary',
                                                'delivered' => 'bg-success',
                                                'cancelled' => 'bg-danger'
                                            ];
                                            ?>
                                            <span class="badge <?php echo $status_badge[$order->status]; ?>">
                                                <?php echo ucfirst($order->status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?php echo site_url('orders/view/' . $order->order_id); ?>"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>View
                                            </a>
                                            <?php if ($order->status == 'pending'): ?>
                                                <a href="<?php echo site_url('orders/cancel/' . $order->order_id); ?>"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to cancel this order?')">
                                                    <i class="fas fa-times me-1"></i>Cancel
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>