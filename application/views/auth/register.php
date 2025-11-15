<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i>Create New Account</h4>
            </div>
            <div class="card-body p-4">
                <?php echo form_open('auth/register', ['id' => 'registerForm']); ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="full_name" name="full_name"
                                value="<?php echo set_value('full_name'); ?>" required placeholder="Enter full name">
                        </div>
                        <?php echo form_error('full_name', '<div class="text-danger small mt-1">', '</div>'); ?>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                            <input type="text" class="form-control" id="username" name="username"
                                value="<?php echo set_value('username'); ?>" required placeholder="Enter username">
                        </div>
                        <?php echo form_error('username', '<div class="text-danger small mt-1">', '</div>'); ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo set_value('email'); ?>" required placeholder="Enter email address">
                    </div>
                    <?php echo form_error('email', '<div class="text-danger small mt-1">', '</div>'); ?>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="tel" class="form-control" id="phone" name="phone"
                            value="<?php echo set_value('phone'); ?>" placeholder="Enter phone number">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="Enter password">
                        </div>
                        <?php echo form_error('password', '<div class="text-danger small mt-1">', '</div>'); ?>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                required placeholder="Enter confirm password">
                        </div>
                        <?php echo form_error('confirm_password', '<div class="text-danger small mt-1">', '</div>'); ?>
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="terms" required>
                    <label class="form-check-label" for="terms">
                        I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a> <span class="text-danger">*</span>
                    </label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Create Account
                    </button>
                </div>
                <?php echo form_close(); ?>

                <div class="text-center mt-3">
                    <p class="mb-0">Already have an account?
                        <a href="<?php echo site_url('auth/login'); ?>" class="text-decoration-none">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>