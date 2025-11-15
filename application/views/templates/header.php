<?php
$cart_count = 0;
if ($this->session->userdata('logged_in')) {
    if (!isset($this->cart_model)) {
        $this->load->model('Cart_model');
    }
    $cart_count = $this->Cart_model->get_cart_count($this->session->userdata('user_id'));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? $title : 'Ecommerce'; ?></title>
    <link rel="icon" href="<?= base_url("assets/icon/icon.png") ?>" type="image/x-icon">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --text-color: #667eea;
            --border-color: #764ba2;
        }

        .bg-primary {
            background: var(--primary-color);
        }

        /* .btn-outline-primary {
            background: var(--primary-color);
        } */

        .border-left-primary {
            background: var(--primary-color);
        }

        .btn {
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .btn:hover {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn.show {
            color: white;
            background: var(--primary-color);
            border-color: var(--border-color);
        }

        .list-group-item.active {
            color: white;
            background: var(--primary-color);
            border-color: var(--border-color);
        }

        .navbar {
            background: var(--primary-color);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .hero-section {
            background: var(--primary-color);
            color: white;
        }

        .card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .product-image {
            height: 200px;
            object-fit: cover;
        }

        .cart-badge,
        .wishlist-badge {
            position: absolute;
            top: -8px;
            right: -8px;
        }

        .footer {
            background: var(--primary-color);
            color: white;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?php echo site_url('home'); ?>">
                <i class="fas fa-shopping-bag me-2"></i>Ecommerce
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo site_url('home'); ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo site_url('home/products'); ?>">Products</a>
                    </li>
                    <?php if ($this->session->userdata('role') == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo site_url('admin'); ?>">
                                <i class="fas fa-cog me-1"></i>Admin Panel
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <ul class="navbar-nav">
                    <?php if ($this->session->userdata('logged_in')): ?>
                        <li class="nav-item">
                            <a class="nav-link position-relative text-white" href="<?php echo site_url('wishlist'); ?>">
                                <i class="fas fa-heart"></i> Wishlist
                                <?php
                                $wishlist_count = 0;
                                if ($this->session->userdata('logged_in')) {
                                    $this->load->model('Wishlist_model');
                                    $wishlist_count = $this->Wishlist_model->get_wishlist_count($this->session->userdata('user_id'));
                                }
                                if ($wishlist_count > 0): ?>
                                    <span class="badge bg-danger wishlist-badge"><?php echo $wishlist_count; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative text-white" href="<?php echo site_url('cart'); ?>">
                                <i class="fas fa-shopping-cart"></i> Cart
                                <?php if ($cart_count > 0): ?>
                                    <span class="badge bg-danger cart-badge"><?php echo $cart_count; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i>
                                <?php echo $this->session->userdata('full_name'); ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="<?php echo site_url('auth/profile'); ?>">
                                        <i class="fas fa-user-circle me-2"></i>Profile
                                    </a></li>
                                <li><a class="dropdown-item" href="<?php echo site_url('orders'); ?>">
                                        <i class="fas fa-receipt me-2"></i>My Orders
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="<?php echo site_url('auth/logout'); ?>">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo site_url('auth/login'); ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo site_url('auth/register'); ?>">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <!-- <main class="min-vh-100"> -->
    <main>
        <div class="container mt-4">
            <!-- Alert Container -->
            <div id="alertContainer"></div>