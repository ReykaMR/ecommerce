<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Manage Products</h2>
    <a href="<?php echo site_url('admin/products/add'); ?>" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add New Product
    </a>
</div>

<div class="card">
    <div class="card-header bg-light text-dark">
        <h5 class="mb-0">Products List</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($products)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo $product->product_id; ?></td>
                                <td>
                                    <?php
                                    $primary_image = $this->Product_image_model->get_primary_image($product->product_id);
                                    $image_url = $primary_image ? base_url($primary_image->image_url) : base_url('assets/images/placeholder.jpg');
                                    ?>
                                    <img src="<?php echo $image_url; ?>" class="img-thumbnail"
                                        alt="<?php echo $product->product_name; ?>"
                                        onerror="this.src='<?php echo base_url('assets/images/placeholder.jpg'); ?>'"
                                        style="width: 50px; height: 50px; object-fit: cover;">
                                </td>
                                <td>
                                    <strong><?php echo $product->product_name; ?></strong>
                                    <br><small
                                        class="text-muted"><?php echo character_limiter($product->description, 50); ?></small>
                                </td>
                                <td><?php echo $product->category_name; ?></td>
                                <td>Rp <?php echo number_format($product->price, 0, ',', '.'); ?></td>
                                <td>
                                    <span class="badge <?php echo $product->stock > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo $product->stock; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?php echo $product->is_active ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo $product->is_active ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo site_url('home/product/' . $product->product_id); ?>"
                                            class="btn btn-outline-primary" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo site_url('admin/products/edit/' . $product->product_id); ?>"
                                            class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo site_url('admin/products/delete/' . $product->product_id); ?>"
                                            class="btn btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No products found</h4>
                <p class="text-muted">Get started by adding your first product.</p>
                <a href="<?php echo site_url('admin/products/add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Product
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>