<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Add New User</h2>
    <a href="<?php echo site_url('admin/users'); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Users
    </a>
</div>

<div class="card">
    <div class="card-header bg-light text-dark">
        <h5 class="mb-0">User Information</h5>
    </div>
    <div class="card-body">
        <?php echo form_open('admin/users/add'); ?>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="full_name" class="form-label">Full Name *</label>
                <input type="text" class="form-control" id="full_name" name="full_name"
                    value="<?php echo set_value('full_name'); ?>" required>
                <?php echo form_error('full_name', '<div class="text-danger small mt-1">', '</div>'); ?>
            </div>

            <div class="col-md-6 mb-3">
                <label for="username" class="form-label">Username *</label>
                <input type="text" class="form-control" id="username" name="username"
                    value="<?php echo set_value('username'); ?>" required>
                <?php echo form_error('username', '<div class="text-danger small mt-1">', '</div>'); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email Address *</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?php echo set_value('email'); ?>" required>
                <?php echo form_error('email', '<div class="text-danger small mt-1">', '</div>'); ?>
            </div>

            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="phone"
                    value="<?php echo set_value('phone'); ?>">
                <?php echo form_error('phone', '<div class="text-danger small mt-1">', '</div>'); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Password *</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <?php echo form_error('password', '<div class="text-danger small mt-1">', '</div>'); ?>
            </div>

            <div class="col-md-6 mb-3">
                <label for="confirm_password" class="form-label">Confirm Password *</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                <?php echo form_error('confirm_password', '<div class="text-danger small mt-1">', '</div>'); ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role *</label>
            <select class="form-select" id="role" name="role" required>
                <option value="">Select Role</option>
                <option value="customer" <?php echo set_value('role') == 'customer' ? 'selected' : ''; ?>>Customer
                </option>
                <option value="admin" <?php echo set_value('role') == 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
            <?php echo form_error('role', '<div class="text-danger small mt-1">', '</div>'); ?>
        </div>

        <div class="d-grid gap-2 d-md-flex">
            <button type="submit" class="btn btn-primary btn-md">
                <i class="fas fa-save me-2"></i>Add User
            </button>
            <a href="<?php echo site_url('admin/users'); ?>" class="btn btn-secondary btn-md ms-md-2">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>