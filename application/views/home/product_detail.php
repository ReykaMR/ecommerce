<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo site_url('home/products'); ?>">Products</a></li>
        <li class="breadcrumb-item active"><?php echo $product->product_name; ?></li>
    </ol>
</nav>

<div class="row">
    <!-- Product Images -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <?php
                $images = $this->Product_image_model->get_images_by_product($product->product_id);
                $primary_image = $this->Product_image_model->get_primary_image($product->product_id);
                $main_image_url = $primary_image ? base_url($primary_image->image_url) : base_url('assets/images/placeholder.jpg');
                ?>

                <img src="<?php echo $main_image_url; ?>" class="img-fluid rounded main-product-image"
                    alt="<?php echo $product->product_name; ?>"
                    onerror="this.src='<?php echo base_url('assets/images/placeholder.jpg'); ?>'"
                    style="max-height: 400px; object-fit: cover;">

                <?php if (!empty($images)): ?>
                    <div class="row mt-3 justify-content-center">
                        <?php foreach ($images as $index => $image): ?>
                            <div class="col-3">
                                <img src="<?php echo base_url($image->image_url); ?>"
                                    class="img-thumbnail thumbnail-image <?php echo $image->is_primary ? 'active' : ''; ?>"
                                    alt="Thumbnail <?php echo $index + 1; ?>"
                                    onerror="this.src='<?php echo base_url('assets/images/placeholder.jpg'); ?>'"
                                    data-main-image="<?php echo base_url($image->image_url); ?>"
                                    style="cursor: pointer; height: 80px; object-fit: cover;">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Product Info -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h1 class="h2 fw-bold"><?php echo $product->product_name; ?></h1>
                <p class="text-muted">Category: <?php echo $product->category_name; ?></p>

                <div class="mb-3">
                    <span class="h3 text-primary fw-bold">Rp
                        <?php echo number_format($product->price, 0, ',', '.'); ?></span>
                </div>

                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="ratings me-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star-half-alt text-warning"></i>
                        </div>
                        <span class="text-muted">(4.5/5.0) • 120 reviews</span>
                    </div>
                </div>

                <p class="mb-4"><?php echo $product->description; ?></p>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <strong class="me-3">Status:</strong>
                            <?php if ($product->stock > 0): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>In Stock (<?php echo $product->stock; ?>)
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger">
                                    <i class="fas fa-times me-1"></i>Out of Stock
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <strong class="me-3">Weight:</strong>
                            <span><?php echo $product->weight; ?> kg</span>
                        </div>
                    </div>
                </div>

                <?php if ($product->stock > 0): ?>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="quantity" class="form-label fw-bold">Quantity</label>
                            <input type="number" class="form-control" id="quantity" value="1" min="1"
                                max="<?php echo $product->stock; ?>">
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex">
                        <button class="btn btn-primary btn-lg flex-fill me-md-2 add-to-cart"
                            data-product-id="<?php echo $product->product_id; ?>">
                            <i class="fas fa-cart-plus me-2"></i>Add to Cart
                        </button>
                        <button class="btn btn-outline-danger btn-lg flex-fill add-to-wishlist"
                            data-product-id="<?php echo $product->product_id; ?>">
                            <i class="fas fa-heart me-2"></i>Add to Wishlist
                        </button>
                    </div>
                <?php else: ?>
                    <button class="btn btn-secondary btn-lg w-100" disabled>
                        <i class="fas fa-times me-2"></i>Out of Stock
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Product Details Tabs -->
<!-- <div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                            data-bs-target="#description" type="button" role="tab">
                            Description
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="specifications-tab" data-bs-toggle="tab"
                            data-bs-target="#specifications" type="button" role="tab">
                            Specifications
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                            type="button" role="tab">
                            Reviews
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="productTabsContent">
                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                        <p><?php echo $product->description; ?></p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua.</p>
                    </div>
                    <div class="tab-pane fade" id="specifications" role="tabpanel">
                        <table class="table table-striped">
                            <tr>
                                <td><strong>Category</strong></td>
                                <td><?php echo $product->category_name; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Weight</strong></td>
                                <td><?php echo $product->weight; ?> kg</td>
                            </tr>
                            <tr>
                                <td><strong>Stock</strong></td>
                                <td><?php echo $product->stock; ?> units</td>
                            </tr>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="reviews" role="tabpanel">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No reviews yet. Be the first to review this product!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<script>
    // Thumbnail click functionality
    $(document).ready(function () {
        $('.thumbnail-image').on('click', function () {
            var mainImageUrl = $(this).data('main-image');
            $('.main-product-image').attr('src', mainImageUrl);
            $('.thumbnail-image').removeClass('active');
            $(this).addClass('active');
        });
    });
</script>

<style>
    .thumbnail-image.active {
        border: 2px solid #007bff;
    }

    .thumbnail-image:hover {
        opacity: 0.8;
    }
</style>