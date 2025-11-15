<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Add New Category</h2>
    <a href="<?php echo site_url('admin/categories'); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Categories
    </a>
</div>

<div class="card">
    <div class="card-header bg-light text-dark">
        <h5 class="mb-0">Category Information</h5>
    </div>
    <div class="card-body">
        <?php echo form_open('admin/categories/add'); ?>
        <div class="mb-3">
            <label for="category_name" class="form-label">Category Name *</label>
            <input type="text" class="form-control" id="category_name" name="category_name"
                value="<?php echo set_value('category_name'); ?>" required>
            <?php echo form_error('category_name', '<div class="text-danger small mt-1">', '</div>'); ?>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"
                rows="3"><?php echo set_value('description'); ?></textarea>
            <?php echo form_error('description', '<div class="text-danger small mt-1">', '</div>'); ?>
        </div>

        <div class="d-grid gap-2 d-md-flex">
            <button type="submit" class="btn btn-primary btn-md">
                <i class="fas fa-save me-2"></i>Add Category
            </button>
            <a href="<?php echo site_url('admin/categories'); ?>" class="btn btn-secondary btn-md ms-md-2">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>