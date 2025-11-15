<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? $title : 'Admin - Ecommerce'; ?></title>
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
            --hover-color: #480091ff;
        }

        .bg-primary {
            background: var(--primary-color);
        }

        .btn {
            color: var(--text-color);
            border: 1px solid var(--border-color);
            background-color: white;
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

        .sidebar {
            min-height: calc(100vh - 56px);
            background: var(--primary-color);
        }

        .sidebar .nav-link {
            color: #fff;
            padding: 0.75rem 1rem;
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: var(--hover-color);
            border-left-color: var(--border-color);
        }

        .sidebar .nav-link.active {
            color: #fff;
            background-color: var(--hover-color);
            border-left-color: var(--border-color);
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }

        .main-content {
            background-color: #f8f9fa;
            min-height: calc(100vh - 56px);
        }

        .stat-card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-dark bg-primary sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo site_url('admin'); ?>">
                <i class="fas fa-cog me-2"></i>Admin Panel
            </a>

            <div class="d-flex">
                <a href="<?php echo site_url('home'); ?>" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-home me-1"></i>Store
                </a>
                <div class="dropdown">
                    <a class="btn btn-outline-light btn-sm dropdown-toggle" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user me-1"></i><?php echo $this->session->userdata('full_name'); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo site_url('auth/profile'); ?>">
                                <i class="fas fa-user-circle me-2"></i>Profile
                            </a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="<?php echo site_url('auth/logout'); ?>">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <nav class="nav flex-column pt-3">
                    <a class="nav-link <?php echo (current_url() == site_url('admin') || current_url() == site_url('admin/dashboard')) ? 'active' : ''; ?>"
                        href="<?php echo site_url('admin/dashboard'); ?>">
                        <i class="fas fa-tachometer-alt"></i>Dashboard
                    </a>
                    <a class="nav-link <?php echo strpos(current_url(), 'admin/products') !== false ? 'active' : ''; ?>"
                        href="<?php echo site_url('admin/products'); ?>">
                        <i class="fas fa-box"></i>Products
                    </a>
                    <a class="nav-link <?php echo strpos(current_url(), 'admin/orders') !== false ? 'active' : ''; ?>"
                        href="<?php echo site_url('admin/orders'); ?>">
                        <i class="fas fa-shopping-cart"></i>Orders
                    </a>
                    <a class="nav-link <?php echo strpos(current_url(), 'admin/categories') !== false ? 'active' : ''; ?>"
                        href="<?php echo site_url('admin/categories'); ?>">
                        <i class="fas fa-tags"></i>Categories
                    </a>
                    <a class="nav-link <?php echo strpos(current_url(), 'admin/users') !== false ? 'active' : ''; ?>"
                        href="<?php echo site_url('admin/users'); ?>">
                        <i class="fas fa-users"></i>Users
                    </a>
                    <a class="nav-link <?php echo strpos(current_url(), 'admin/file-cleanup') !== false ? 'active' : ''; ?>"
                        href="<?php echo site_url('admin/file-cleanup'); ?>">
                        <i class="fas fa-broom"></i>File Cleanup
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content p-4">
                <!-- Alert Container -->
                <div id="alertContainer"></div>