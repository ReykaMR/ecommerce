<div class="row justify-content-center">
    <div class="col-lg-8">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>">Home</a></li>
                <li class="breadcrumb-item active">My Profile</li>
            </ol>
        </nav>

        <h2 class="fw-bold mb-4"><i class="fas fa-user-circle me-2"></i>My Profile</h2>

        <div class="card">
            <div class="card-header bg-light text-dark">
                <h5 class="mb-0">Profile Information</h5>
            </div>
            <div class="card-body">
                <?php echo form_open_multipart('auth/update_profile'); ?>
                <div class="row">
                    <!-- Avatar Section -->
                    <div class="col-md-3 text-center mb-4">
                        <div class="mb-3">
                            <?php if ($user->avatar): ?>
                                <img src="<?php echo base_url($user->avatar); ?>" class="img-thumbnail rounded-circle"
                                    alt="Avatar" style="width: 150px; height: 150px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width: 150px; height: 150px;">
                                    <i class="fas fa-user fa-3x text-light"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="avatar" class="form-label small">Change Avatar</label>
                            <input type="file" class="form-control form-control-sm" id="avatar" name="avatar"
                                accept="image/*">
                            <div class="form-text">JPG, PNG, max 2MB</div>
                        </div>
                    </div>

                    <!-- Profile Form -->
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="full_name" name="full_name"
                                    value="<?php echo set_value('full_name', $user->full_name); ?>" required>
                                <?php echo form_error('full_name', '<div class="text-danger small mt-1">', '</div>'); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username"
                                    value="<?php echo $user->username; ?>" readonly>
                                <div class="form-text">Username cannot be changed</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo set_value('email', $user->email); ?>" required>
                                <?php echo form_error('email', '<div class="text-danger small mt-1">', '</div>'); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                    value="<?php echo set_value('phone', $user->phone); ?>">
                                <?php echo form_error('phone', '<div class="text-danger small mt-1">', '</div>'); ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Account Type</label>
                            <input type="text" class="form-control" value="<?php echo ucfirst($user->role); ?>"
                                readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Member Since</label>
                            <input type="text" class="form-control"
                                value="<?php echo date('F d, Y', strtotime($user->created_at)); ?>" readonly>
                        </div>

                        <div class="d-grid gap-2 d-md-flex">
                            <button type="submit" class="btn btn-primary btn-md">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                            <a href="<?php echo site_url('auth/logout'); ?>"
                                class="btn btn-outline-danger btn-md ms-md-2">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

        <!-- Account Statistics -->
        <div class="row mt-4">
            <div class="col-md-4 mb-3">
                <div class="card text-center bg-light text-primary">
                    <div class="card-body">
                        <i class="fas fa-shopping-bag fa-2x mb-3"></i>
                        <h4><?php echo $order_count ?? 0; ?></h4>
                        <p class="mb-0">Total Orders</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center bg-light text-success">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-2x mb-3"></i>
                        <h4><?php echo $completed_orders ?? 0; ?></h4>
                        <p class="mb-0">Completed</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center bg-light text-warning">
                    <div class="card-body">
                        <i class="fas fa-clock fa-2x mb-3"></i>
                        <h4><?php echo $pending_orders ?? 0; ?></h4>
                        <p class="mb-0">Pending</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>