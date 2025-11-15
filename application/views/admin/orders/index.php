<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Manage Orders</h2>
</div>

<div class="card">
    <div class="card-header bg-light text-dark">
        <h5 class="mb-0">Orders List</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($orders)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><strong><?php echo $order->order_number; ?></strong></td>
                                <td>
                                    <?php echo $order->full_name; ?>
                                    <br><small class="text-muted"><?php echo $order->email; ?></small>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($order->created_at)); ?></td>
                                <td>Rp <?php echo number_format($order->total_amount, 0, ',', '.'); ?></td>
                                <td>
                                    <form action="<?php echo site_url('admin/orders/update_status/' . $order->order_id); ?>"
                                        method="post" class="d-inline">
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="pending" <?php echo $order->status == 'pending' ? 'selected' : ''; ?>>
                                                Pending</option>
                                            <option value="paid" <?php echo $order->status == 'paid' ? 'selected' : ''; ?>>Paid
                                            </option>
                                            <option value="processing" <?php echo $order->status == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                            <option value="shipped" <?php echo $order->status == 'shipped' ? 'selected' : ''; ?>>
                                                Shipped</option>
                                            <option value="delivered" <?php echo $order->status == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                            <option value="cancelled" <?php echo $order->status == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('orders/view/' . $order->order_id); ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No orders found</h4>
                <p class="text-muted">Orders will appear here when customers place them.</p>
            </div>
        <?php endif; ?>
    </div>
</div>