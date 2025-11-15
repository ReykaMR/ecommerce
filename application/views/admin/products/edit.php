<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Edit Product</h2>
    <a href="<?php echo site_url('admin/products'); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Products
    </a>
</div>

<div class="card">
    <div class="card-header bg-light text-dark">
        <h5 class="mb-0">Edit Product Information</h5>
    </div>
    <div class="card-body">
        <?php echo form_open_multipart('admin/products/edit/' . $product->product_id); ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="product_name" class="form-label">Product Name *</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" 
                           value="<?php echo set_value('product_name', $product->product_name); ?>" required>
                    <?php echo form_error('product_name', '<div class="text-danger small mt-1">', '</div>'); ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Category *</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category->category_id; ?>" 
                                <?php echo set_value('category_id', $product->category_id) == $category->category_id ? 'selected' : ''; ?>>
                                <?php echo $category->category_name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo form_error('category_id', '<div class="text-danger small mt-1">', '</div>'); ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo set_value('description', $product->description); ?></textarea>
                <?php echo form_error('description', '<div class="text-danger small mt-1">', '</div>'); ?>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="price" class="form-label">Price *</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="price" name="price" 
                               value="<?php echo set_value('price', $product->price); ?>" step="0.01" min="0" required>
                    </div>
                    <?php echo form_error('price', '<div class="text-danger small mt-1">', '</div>'); ?>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="stock" class="form-label">Stock *</label>
                    <input type="number" class="form-control" id="stock" name="stock" 
                           value="<?php echo set_value('stock', $product->stock); ?>" min="0" required>
                    <?php echo form_error('stock', '<div class="text-danger small mt-1">', '</div>'); ?>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="weight" class="form-label">Weight (kg)</label>
                    <input type="number" class="form-control" id="weight" name="weight" 
                           value="<?php echo set_value('weight', $product->weight); ?>" step="0.01" min="0">
                    <?php echo form_error('weight', '<div class="text-danger small mt-1">', '</div>'); ?>
                </div>
            </div>

            <!-- Current Images -->
            <?php if (!empty($images)): ?>
            <div class="mb-4">
                <label class="form-label fw-bold">Current Images</label>
                <div class="row">
                    <?php foreach ($images as $image): ?>
                        <div class="col-md-3 mb-3">
                            <div class="card position-relative">
                                <img src="<?php echo base_url($image->image_url); ?>" 
                                    class="card-img-top" 
                                    alt="Product Image"
                                    onerror="this.src='<?php echo base_url('assets/images/placeholder.jpg'); ?>'"
                                    style="height: 150px; object-fit: cover;">
                                <div class="card-body text-center">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input primary-image-radio" 
                                            type="radio" 
                                            name="primary_image" 
                                            value="<?php echo $image->image_id; ?>" 
                                            <?php echo $image->is_primary ? 'checked' : ''; ?>
                                            data-product-id="<?php echo $product->product_id; ?>">
                                        <label class="form-check-label small">Set as Primary</label>
                                    </div>
                                    <button type="button" 
                                            class="btn btn-danger btn-sm delete-image-btn"
                                            data-image-id="<?php echo $image->image_id; ?>"
                                            data-product-name="<?php echo $product->product_name; ?>">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="mb-4">
                <label for="product_images" class="form-label">Add More Images</label>
                <input type="file" class="form-control" id="product_images" name="product_images[]" multiple accept="image/*">
                <div class="form-text">You can select multiple images (JPG, PNG, GIF, WebP). Max 2MB per image.</div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                       <?php echo set_value('is_active', $product->is_active) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="is_active">Product is active</label>
            </div>

            <div class="d-grid gap-2 d-md-flex">
                <button type="submit" class="btn btn-primary btn-md">
                    <i class="fas fa-save me-2"></i>Update Product
                </button>
                <a href="<?php echo site_url('admin/products'); ?>" class="btn btn-secondary btn-md ms-md-2">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
$(document).ready(function() {
    // Set primary image
    $('.primary-image-radio').on('change', function() {
        var imageId = $(this).val();
        var productId = $(this).data('product-id');
        
        $.ajax({
            url: '<?php echo site_url('admin/set_primary_image'); ?>',
            method: 'POST',
            data: {
                image_id: imageId,
                product_id: productId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                } else {
                    showAlert('danger', response.message);
                }
            },
            error: function() {
                showAlert('danger', 'An error occurred. Please try again.');
            }
        });
    });

    // Delete image
    $('.delete-image-btn').on('click', function() {
        var imageId = $(this).data('image-id');
        var productName = $(this).data('product-name');
        
        if (confirm('Are you sure you want to delete this image from "' + productName + '"?')) {
            window.location.href = '<?php echo site_url('admin/products/delete_image/'); ?>' + imageId;
        }
    });
});
</script>