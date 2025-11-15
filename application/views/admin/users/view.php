<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">User Details</h2>
    <a href="<?php echo site_url('admin/users'); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Users
    </a>
</div>

<div class="row">
    <div class="col-lg-4 mb-4">
        <!-- User Profile Card -->
        <div class="card">
            <div class="card-header bg-light text-dark text-center">
                <h5 class="mb-0">User Profile</h5>
            </div>
            <div class="card-body text-center">
                <?php if ($user->avatar): ?>
                    <img src="<?php echo base_url($user->avatar); ?>" class="img-thumbnail rounded-circle mb-3" alt="Avatar"
                        style="width: 150px; height: 150px; object-fit: cover;">
                <?php else: ?>
                    <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 150px; height: 150px;">
                        <i class="fas fa-user fa-3x text-light"></i>
                    </div>
                <?php endif; ?>

                <h4><?php echo $user->full_name; ?></h4>
                <p class="text-muted">@<?php echo $user->username; ?></p>

                <div class="mb-3">
                    <span class="badge <?php echo $user->role == 'admin' ? 'bg-danger' : 'bg-secondary'; ?> fs-6">
                        <?php echo ucfirst($user->role); ?>
                    </span>
                </div>

                <div class="text-start">
                    <p><strong><i class="fas fa-envelope me-2"></i>Email:</strong><br>
                        <?php echo $user->email; ?></p>

                    <?php if ($user->phone): ?>
                        <p><strong><i class="fas fa-phone me-2"></i>Phone:</strong><br>
                            <?php echo $user->phone; ?></p>
                    <?php endif; ?>

                    <p><strong><i class="fas fa-calendar me-2"></i>Registered:</strong><br>
                        <?php echo date('F d, Y', strtotime($user->created_at)); ?></p>

                    <p><strong><i class="fas fa-clock me-2"></i>Last Updated:</strong><br>
                        <?php echo date('F d, Y H:i', strtotime($user->updated_at)); ?></p>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <a href="<?php echo site_url('admin/users/edit/' . $user->user_id); ?>" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit User
                    </a>
                    <?php if ($user->user_id != $this->session->userdata('user_id')): ?>
                        <a href="<?php echo site_url('admin/users/delete/' . $user->user_id); ?>" class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to delete this user?')">
                            <i class="fas fa-trash me-2"></i>Delete User
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- User Statistics -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card text-center bg-light text-primary">
                    <div class="card-body">
                        <i class="fas fa-shopping-bag fa-2x mb-3"></i>
                        <h4><?php echo count($user_orders); ?></h4>
                        <p class="mb-0">Total Orders</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center bg-light text-success">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-2x mb-3"></i>
                        <h4>
                            <?php
                            $completed = 0;
                            foreach ($user_orders as $order) {
                                if ($order->status == 'delivered')
                                    $completed++;
                            }
                            echo $completed;
                            ?>
                        </h4>
                        <p class="mb-0">Completed</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center bg-light text-warning">
                    <div class="card-body">
                        <i class="fas fa-clock fa-2x mb-3"></i>
                        <h4>
                            <?php
                            $pending = 0;
                            foreach ($user_orders as $order) {
                                if (in_array($order->status, ['pending', 'paid', 'processing']))
                                    $pending++;
                            }
                            echo $pending;
                            ?>
                        </h4>
                        <p class="mb-0">Pending</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Orders -->
        <div class="card">
            <div class="card-header bg-light text-dark">
                <h5 class="mb-0">Recent Orders</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($user_orders)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($user_orders as $order): ?>
                                    <tr>
                                        <td><strong><?php echo $order->order_number; ?></strong></td>
                                        <td><?php echo date('M d, Y', strtotime($order->created_at)); ?></td>
                                        <td>Rp <?php echo number_format($order->total_amount, 0, ',', '.'); ?></td>
                                        <td>
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
                                                ?>">
                                                <?php echo ucfirst($order->status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?php echo site_url('orders/view/' . $order->order_id); ?>"
                                                class="btn btn-sm btn-outline-primary">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4">No orders found for this user.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>