<div class="row">
    <!-- Sidebar Filters -->
    <div class="col-lg-3 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filters</h5>
            </div>
            <div class="card-body">
                <!-- Categories -->
                <div class="mb-4">
                    <h6 class="fw-bold">Categories</h6>
                    <div class="list-group list-group-flush">
                        <a href="<?php echo site_url('home/products'); ?>"
                            class="list-group-item list-group-item-action <?php echo !$this->input->get('category') && !$this->input->get('search') ? 'active' : ''; ?>">
                            All Categories
                        </a>
                        <?php foreach ($categories as $category): ?>
                            <a href="<?php echo site_url('home/products?category=' . $category->category_id); ?>"
                                class="list-group-item list-group-item-action <?php echo $this->input->get('category') == $category->category_id ? 'active' : ''; ?>">
                                <?php echo $category->category_name; ?>
                                <?php if (isset($category->product_count)): ?>
                                    <span class="badge bg-secondary float-end"><?php echo $category->product_count; ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Search -->
                <div class="mb-4">
                    <h6 class="fw-bold">Search</h6>
                    <form action="<?php echo site_url('home/products'); ?>" method="get">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search products..."
                                value="<?php echo $this->input->get('search'); ?>">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Clear Filters -->
                <?php if ($this->input->get('category') || $this->input->get('search')): ?>
                    <div class="mb-3">
                        <a href="<?php echo site_url('home/products'); ?>" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="fas fa-times me-1"></i>Clear Filters
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="col-lg-9">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">
                    <?php if ($this->input->get('category')): ?>
                        <?php
                        $category_name = '';
                        foreach ($categories as $cat) {
                            if ($cat->category_id == $this->input->get('category')) {
                                $category_name = $cat->category_name;
                                break;
                            }
                        }
                        echo $category_name . ' Products';
                        ?>
                    <?php elseif ($this->input->get('search')): ?>
                        Search Results for "<?php echo htmlspecialchars($this->input->get('search')); ?>"
                    <?php else: ?>
                        All Products
                    <?php endif; ?>
                </h2>
                <p class="text-muted mb-0">
                    Showing
                    <?php
                    $start = $current_page + 1;
                    $end = min($current_page + $per_page, $total_products);
                    echo $start . ' - ' . $end . ' of ' . $total_products . ' products';
                    ?>
                </p>
            </div>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Sort By
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?php echo current_url() . '?sort=name_asc'; ?>">Name: A to Z</a>
                    </li>
                    <li><a class="dropdown-item" href="<?php echo current_url() . '?sort=name_desc'; ?>">Name: Z to
                            A</a></li>
                    <li><a class="dropdown-item" href="<?php echo current_url() . '?sort=price_asc'; ?>">Price: Low to
                            High</a></li>
                    <li><a class="dropdown-item" href="<?php echo current_url() . '?sort=price_desc'; ?>">Price: High to
                            Low</a></li>
                    <li><a class="dropdown-item" href="<?php echo current_url() . '?sort=newest'; ?>">Newest First</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Products -->
        <div class="row">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 product-card">
                            <?php
                            // Get product image
                            $primary_image = $this->Product_image_model->get_primary_image($product->product_id);
                            if (!$primary_image) {
                                $primary_image = $this->Product_image_model->get_first_image($product->product_id);
                            }
                            $image_url = $primary_image ? base_url($primary_image->image_url) : base_url('assets/images/placeholder.jpg');
                            ?>
                            <img src="<?php echo $image_url; ?>" class="card-img-top product-image"
                                alt="<?php echo $product->product_name; ?>"
                                onerror="this.src='<?php echo base_url('assets/images/placeholder.jpg'); ?>'">
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title"><?php echo $product->product_name; ?></h6>
                                <p class="card-text flex-grow-1 small text-muted">
                                    <?php echo character_limiter($product->description, 60); ?>
                                </p>
                                <div class="mt-auto">
                                    <p class="card-text fw-bold text-primary h6 mb-2">
                                        Rp <?php echo number_format($product->price, 0, ',', '.'); ?>
                                    </p>
                                    <div class="d-grid gap-1">
                                        <a href="<?php echo site_url('home/product/' . $product->product_id); ?>"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>Details
                                        </a>
                                        <button class="btn btn-outline-danger btn-sm add-to-wishlist"
                                            data-product-id="<?php echo $product->product_id; ?>">
                                            <i class="fas fa-heart me-1"></i>Wishlist
                                        </button>
                                        <?php if ($product->stock > 0): ?>
                                            <button class="btn btn-primary btn-sm add-to-cart"
                                                data-product-id="<?php echo $product->product_id; ?>">
                                                <i class="fas fa-cart-plus me-1"></i>Add to Cart
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                <i class="fas fa-times me-1"></i>Out of Stock
                                            </button>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <h4>No products found</h4>
                        <p class="mb-3">Try adjusting your search or filter criteria.</p>
                        <a href="<?php echo site_url('home/products'); ?>" class="btn btn-primary">
                            <i class="fas fa-undo me-2"></i>View All Products
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if (isset($links) && !empty($links) && $total_products > $per_page): ?>
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Products pagination">
                    <?php echo $links; ?>
                </nav>
            </div>

            <!-- Pagination Info -->
            <div class="text-center mt-3">
                <p class="text-muted small">
                    Page <?php echo ceil(($current_page + 1) / $per_page); ?> of
                    <?php echo ceil($total_products / $per_page); ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>

<script>
    // Additional functionality specific to products page
    $(document).ready(function () {
        $('.list-group-item, [name="search"]').on('click keyup', function () {
            setTimeout(() => {
                clearAlerts();
            }, 1000);
        });

        $('.quantity-input').on('change', function () {
            let quantity = $(this).val();
            if (quantity < 1) {
                $(this).val(1);
            }
        });
    });
</script>