<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i>Login to Your Account</h4>
            </div>
            <div class="card-body p-4">
                <?php echo form_open('auth/login'); ?>
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
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" required
                            placeholder="Enter password">
                    </div>
                    <?php echo form_error('password', '<div class="text-danger small mt-1">', '</div>'); ?>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                </div>
                <?php echo form_close(); ?>

                <div class="text-center mt-3">
                    <p class="mb-0">
                        Don't have an account?
                        <a href="<?php echo site_url('auth/register'); ?>" class="text-decoration-none">
                            Register here
                        </a>
                    </p>
                </div>

                <div class="text-start mt-3 alert alert-info">
                    <p class="mb-1 fw-bold fs-5">Demo Credentials:</p>
                    <p class="mb-0">Admin: admin@example.com / admin123</p>
                    <p class="mb-0">User 1: user1@example.com / user111</p>
                    <p class="mb-0">User 2: user2@example.com / user222</p>
                    <p class="mb-0">User 3: user3@example.com / user333</p>
                </div>
            </div>
        </div>
    </div>
</div>