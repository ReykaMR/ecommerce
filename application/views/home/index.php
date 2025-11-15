<!-- Hero Section -->
<section class="hero-section py-5 mb-5 rounded-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-center">
                <h1 class="display-4 fw-bold mb-4">Welcome to Our Ecommerce Store</h1>
                <p class="lead mb-4">Discover amazing products at great prices. Shop with confidence and enjoy fast
                    delivery.</p>
                <a href="<?php echo site_url('home/products'); ?>" class="btn btn-light btn-lg me-3">
                    <i class="fas fa-shopping-bag me-2"></i>Shop Now
                </a>
                <a href="#featured" class="btn btn-outline-light btn-lg text-white">
                    <i class="fas fa-star me-2"></i>Featured Products
                </a>
            </div>
            <div class="col-lg-6 text-center">
                <img src="<?php echo base_url('assets/images/hero-placeholder.jpg'); ?>" alt="Hero Image"
                    class="img-fluid rounded-3 shadow"
                    onerror="this.src='<?php echo base_url('assets/images/placeholder.jpg'); ?>'">
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section id="featured" class="mb-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Featured Products</h2>
                <p class="text-muted">Check out our most popular items</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <?php if (!empty($featured_products)): ?>
                <?php foreach ($featured_products as $product): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card h-100">
                            <?php
                            if (!isset($this->product_image_model)) {
                                $this->load->model('Product_image_model');
                            }
                            $primary_image = $this->Product_image_model->get_primary_image($product->product_id);
                            if (!$primary_image) {
                                $primary_image = $this->Product_image_model->get_first_image($product->product_id);
                            }
                            $image_url = $primary_image ? base_url($primary_image->image_url) : base_url('assets/images/placeholder.jpg');
                            ?>
                            <img src="<?php echo $image_url; ?>" class="card-img-top product-image"
                                alt="<?php echo htmlspecialchars($product->product_name); ?>"
                                onerror="this.src='<?php echo base_url('assets/images/placeholder.jpg'); ?>'">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo htmlspecialchars($product->product_name); ?></h5>
                                <p class="card-text flex-grow-1 text-muted">
                                    <?php echo character_limiter($product->description, 80); ?>
                                </p>
                                <div class="mt-auto">
                                    <p class="card-text fw-bold text-primary h5">
                                        Rp <?php echo number_format($product->price, 0, ',', '.'); ?>
                                    </p>
                                    <div class="d-grid gap-2">
                                        <a href="<?php echo site_url('home/product/' . $product->product_id); ?>"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>View Details
                                        </a>
                                        <button class="btn btn-primary btn-sm add-to-cart"
                                            data-product-id="<?php echo $product->product_id; ?>">
                                            <i class="fas fa-cart-plus me-1"></i>Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>No featured products available at the moment.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="mb-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Shop by Category</h2>
                <p class="text-muted">Browse products by category</p>
            </div>
        </div>

        <div class="row">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="category-icon mb-3">
                                    <i class="fas fa-folder fa-3x text-primary"></i>
                                </div>
                                <h5 class="card-title"><?php echo $category->category_name; ?></h5>
                                <p class="card-text text-muted"><?php echo $category->description; ?></p>
                                <span class="badge bg-secondary"><?php echo $category->product_count; ?> products</span>
                                <div class="mt-3">
                                    <a href="<?php echo site_url('home/products?category=' . $category->category_id); ?>"
                                        class="btn btn-outline-primary">
                                        Browse Category
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>No categories available.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>