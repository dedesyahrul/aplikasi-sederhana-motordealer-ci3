<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dealer Motor' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        :root {
            --header-height: 60px;
            --sidebar-width: 250px;
        }

        body {
            min-height: 100vh;
            padding-top: var(--header-height);
            overflow-x: hidden;
        }

        .sidebar {
            position: fixed;
            top: var(--header-height);
            bottom: 0;
            left: 0;
            width: var(--sidebar-width);
            z-index: 100;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            transition: all 0.3s;
            background-color: #f8f9fa;
            overflow-y: auto;
            height: calc(100vh - var(--header-height));
            padding: 1rem 0;
        }

        .sidebar .nav {
            display: flex;
            flex-direction: column;
            padding: 0;
            margin: 0;
        }

        .sidebar .nav-item {
            width: 100%;
            margin: 0;
        }

        .nav-link {
            padding: 0.75rem 1.5rem;
            color: #333;
            transition: all 0.3s;
            border-radius: 0;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            align-items: center;
        }

        .nav-link:hover {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .nav-link.active {
            background-color: #0d6efd;
            color: white;
            margin: 0;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        @media (max-width: 767.98px) {
            .sidebar {
                left: calc(-1 * var(--sidebar-width));
                z-index: 1030;
            }

            .sidebar.show {
                left: 0;
            }

            .content-wrapper {
                margin-left: 0 !important;
                width: 100%;
            }
        }

        .content-wrapper {
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
            min-height: calc(100vh - var(--header-height));
            padding: 24px;
            background-color: #e9ecef;
            width: calc(100% - var(--sidebar-width));
            position: relative;
        }

        @media (max-width: 767.98px) {
            .content-wrapper {
                margin-left: 0 !important;
                width: 100%;
            }
        }

        .container-fluid {
            padding: 0;
            height: 100%;
        }

        .card {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            margin-bottom: 1.5rem;
            background-color: #ffffff;
            border: none;
            border-radius: 0.375rem;
        }

        .card-header {
            border-bottom: 1px solid rgba(0,0,0,.125);
            background-color: transparent;
            padding: 1.25rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        .table-responsive {
            background-color: #ffffff;
            border-radius: 0.375rem;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        }

        .navbar {
            height: var(--header-height);
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }

        .navbar-brand {
            font-size: 1.25rem;
            padding-top: 0;
            padding-bottom: 0;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 400;
            margin: 0;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .btn-icon {
            padding: 0.25rem 0.5rem;
        }

        .footer {
            background-color: #fff;
            border-top: 1px solid #dee2e6;
            padding: 1rem;
            color: #6c757d;
        }

        /* Form Styles */
        .form-label {
            font-weight: 500;
        }

        .required:after {
            content: " *";
            color: #dc3545;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* DataTables Custom Styles */
        .dataTables_wrapper .dataTables_length select {
            min-width: 60px;
        }

        .dataTables_wrapper .dataTables_filter input {
            margin-left: 0.5rem;
        }

        .dataTables_wrapper .dataTables_info {
            padding-top: 1rem;
        }

        /* Alert Styles */
        .alert {
            margin-bottom: 1.5rem;
        }

        /* Badge Styles */
        .badge {
            padding: 0.5em 0.75em;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler border-0" type="button" id="sidebarToggle">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="<?= base_url() ?>">Dealer Motor</a>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <ul class="nav flex-column">
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
    </div>

    <!-- Main Content -->
    
        <div class="container-fluid" style="background-color: #e9ecef;">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        </div>
    

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle sidebar
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickInsideToggle = sidebarToggle.contains(event.target);
            
            if (!isClickInsideSidebar && !isClickInsideToggle && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 767.98) {
                sidebar.classList.remove('show');
            }
        });
    });
    </script>
</body>
</html>
