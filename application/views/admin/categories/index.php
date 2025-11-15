<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Manage Categories</h2>
    <a href="<?php echo site_url('admin/categories/add'); ?>" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add New Category
    </a>
</div>

<div class="card">
    <div class="card-header bg-light text-dark">
        <h5 class="mb-0">Categories List</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($categories)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th>Product Count</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?php echo $category->category_id; ?></td>
                                <td><strong><?php echo $category->category_name; ?></strong></td>
                                <td><?php echo $category->description ?: '-'; ?></td>
                                <td>
                                    <span class="badge bg-primary">
                                        <?php
                                        $product_count = $this->Product_model->get_total_products_by_category($category->category_id);
                                        echo $product_count;
                                        ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($category->created_at)); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo site_url('admin/categories/edit/' . $category->category_id); ?>"
                                            class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo site_url('admin/categories/delete/' . $category->category_id); ?>"
                                            class="btn btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
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
                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No categories found</h4>
                <p class="text-muted">Get started by adding your first category.</p>
                <a href="<?php echo site_url('admin/categories/add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Category
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>