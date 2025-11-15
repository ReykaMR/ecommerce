<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Add New Product</h2>
    <a href="<?php echo site_url('admin/products'); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Products
    </a>
</div>

<div class="card">
    <div class="card-header bg-light text-dark">
        <h5 class="mb-0">Product Information</h5>
    </div>
    <div class="card-body">
        <?php echo form_open_multipart('admin/products/add'); ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="product_name" class="form-label">Product Name *</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" 
                           value="<?php echo set_value('product_name'); ?>" required>
                    <?php echo form_error('product_name', '<div class="text-danger small mt-1">', '</div>'); ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Category *</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category->category_id; ?>" 
                                <?php echo set_value('category_id') == $category->category_id ? 'selected' : ''; ?>>
                                <?php echo $category->category_name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo form_error('category_id', '<div class="text-danger small mt-1">', '</div>'); ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo set_value('description'); ?></textarea>
                <?php echo form_error('description', '<div class="text-danger small mt-1">', '</div>'); ?>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="price" class="form-label">Price *</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="price" name="price" 
                               value="<?php echo set_value('price'); ?>" step="0.01" min="0" required>
                    </div>
                    <?php echo form_error('price', '<div class="text-danger small mt-1">', '</div>'); ?>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="stock" class="form-label">Stock *</label>
                    <input type="number" class="form-control" id="stock" name="stock" 
                           value="<?php echo set_value('stock'); ?>" min="0" required>
                    <?php echo form_error('stock', '<div class="text-danger small mt-1">', '</div>'); ?>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="weight" class="form-label">Weight (kg)</label>
                    <input type="number" class="form-control" id="weight" name="weight" 
                           value="<?php echo set_value('weight'); ?>" step="0.01" min="0">
                    <?php echo form_error('weight', '<div class="text-danger small mt-1">', '</div>'); ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="product_images" class="form-label">Product Images</label>
                <input type="file" class="form-control" id="product_images" name="product_images[]" multiple accept="image/*">
                <div class="form-text">You can select multiple images. The first image will be set as primary.</div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                <label class="form-check-label" for="is_active">Product is active</label>
            </div>

            <div class="d-grid gap-2 d-md-flex">
                <button type="submit" class="btn btn-primary btn-md">
                    <i class="fas fa-save me-2"></i>Add Product
                </button>
                <a href="<?php echo site_url('admin/products'); ?>" class="btn btn-secondary btn-md ms-md-2">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>