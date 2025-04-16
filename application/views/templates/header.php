<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dealer Motor' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --header-height: 70px;
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --light-bg: #f1f5f9;
            --dark-bg: #0f172a;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            padding-top: var(--header-height);
            background-color: var(--light-bg);
        }

        /* Navbar Styles */
        .navbar {
            height: var(--header-height);
            background: var(--dark-bg);
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            padding: 0.5rem 1rem;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 600;
            color: white !important;
        }

        .navbar-nav .nav-link {
            color: rgba(255,255,255,.8) !important;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: white !important;
            background: rgba(255,255,255,.1);
        }

        .navbar-nav .nav-link.active {
            color: white !important;
            background: var(--primary-color);
        }

        .navbar-profile {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .navbar-profile:hover {
            background: rgba(255,255,255,.1);
        }

        .profile-image {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-right: 0.75rem;
            object-fit: cover;
        }

        .profile-info {
            line-height: 1.2;
        }

        .profile-name {
            font-weight: 500;
            font-size: 0.9rem;
            color: white;
            margin: 0;
        }

        .profile-role {
            font-size: 0.8rem;
            color: rgba(255,255,255,.7);
            margin: 0;
        }

        /* Content Wrapper */
        .content-wrapper {
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
        }

        /* Card & Components */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,.05);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1.25rem;
            border-radius: 1rem 1rem 0 0 !important;
        }

        .card-body {
            padding: 1.25rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.1),0 2px 4px -1px rgba(0,0,0,.06);
            border-radius: 0.5rem;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .dropdown-item:hover {
            background-color: var(--light-bg);
        }

        .dropdown-item i {
            width: 1.25rem;
            text-align: center;
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="fas fa-motorcycle me-2"></i>
                Dealer Motor
            </a>
            
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= $this->uri->segment(1) == '' || $this->uri->segment(1) == 'dashboard' ? 'active' : '' ?>" href="<?= base_url() ?>">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $this->uri->segment(1) == 'motor' ? 'active' : '' ?>" href="<?= base_url('motor') ?>">
                            <i class="fas fa-motorcycle"></i> Motor
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $this->uri->segment(1) == 'sparepart' ? 'active' : '' ?>" href="<?= base_url('sparepart') ?>">
                            <i class="fas fa-cogs"></i> Sparepart
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $this->uri->segment(1) == 'category' ? 'active' : '' ?>" href="<?= base_url('category') ?>">
                            <i class="fas fa-tags"></i> Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $this->uri->segment(1) == 'customer' ? 'active' : '' ?>" href="<?= base_url('customer') ?>">
                            <i class="fas fa-users"></i> Pelanggan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $this->uri->segment(1) == 'sales' ? 'active' : '' ?>" href="<?= base_url('sales') ?>">
                            <i class="fas fa-shopping-cart"></i> Penjualan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $this->uri->segment(1) == 'service' ? 'active' : '' ?>" href="<?= base_url('service') ?>">
                            <i class="fas fa-wrench"></i> Servis
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <a href="#" class="navbar-profile dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name=Admin&background=2563eb&color=fff" alt="Profile" class="profile-image">
                            <div class="profile-info navbar-profile-text">
                                <p class="profile-name">Admin</p>
                                <p class="profile-role">Administrator</p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content Wrapper -->
    <div >
        <!-- Flash Messages -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?= $this->session->flashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= $this->session->flashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
