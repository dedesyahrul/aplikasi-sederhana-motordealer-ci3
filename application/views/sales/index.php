<?php $this->load->view('templates/header'); ?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Penjualan</h1>
        <a href="<?= base_url('sales/add') ?>" class="btn btn-primary">
            <i class="fas fa-plus fa-sm me-2"></i>Tambah Penjualan
        </a>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?= $this->session->flashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?= $this->session->flashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="all-tab" data-bs-toggle="tab" href="#all" role="tab">
                        <i class="fas fa-list me-1"></i>Semua Penjualan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="motor-tab" data-bs-toggle="tab" href="#motor" role="tab">
                        <i class="fas fa-motorcycle me-1"></i>Penjualan Motor
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="sparepart-tab" data-bs-toggle="tab" href="#sparepart" role="tab">
                        <i class="fas fa-cogs me-1"></i>Penjualan Sparepart
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <!-- All Sales Tab -->
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="allTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="50" class="text-center">No</th>
                                    <th>No. Faktur</th>
                                    <th>Tanggal</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($sales) && !empty($sales)): ?>
                                    <?php $no = 1; foreach ($sales as $sale): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= $sale->invoice_number ?? '-' ?></td>
                                        <td><?= date('d/m/Y', strtotime($sale->created_at)) ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-circle text-gray-500 me-2 fa-lg"></i>
                                                <div>
                                                    <div class="fw-bold"><?= $sale->nama_customer ?></div>
                                                    <small class="text-muted"><?= $sale->customer_phone ?? '' ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php 
                                            $items = explode('||', $sale->items_list);
                                            foreach ($items as $item) {
                                                if (strpos($item, 'motor:') !== false) {
                                                    $motor = str_replace('motor:', '', $item);
                                                    echo '<div class="badge bg-primary mb-1"><i class="fas fa-motorcycle me-1"></i>'.$motor.'</div><br>';
                                                } else if (strpos($item, 'sparepart:') !== false) {
                                                    $sparepart = str_replace('sparepart:', '', $item);
                                                    echo '<div class="badge bg-info mb-1"><i class="fas fa-cogs me-1"></i>'.$sparepart.'</div><br>';
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td class="text-end">
                                            <div class="fw-bold">Rp <?= number_format($sale->total_amount, 0, ',', '.') ?></div>
                                            <?php
                                            $payment_icons = [
                                                'cash' => '<i class="fas fa-money-bill-wave text-success"></i> Tunai',
                                                'transfer' => '<i class="fas fa-university text-primary"></i> Transfer',
                                                'credit_card' => '<i class="fas fa-credit-card text-info"></i> Kartu Kredit'
                                            ];
                                            ?>
                                            <small class="text-muted">
                                                <?= $payment_icons[$sale->payment_method] ?? ucfirst($sale->payment_method) ?>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $status_badges = [
                                                'pending' => '<div class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Menunggu</div>',
                                                'completed' => '<div class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Selesai</div>',
                                                'cancelled' => '<div class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Batal</div>'
                                            ];
                                            echo $status_badges[$sale->status] ?? ucfirst($sale->status);
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="<?= base_url('sales/view/'.$sale->id) ?>" 
                                                   class="btn btn-info btn-sm"
                                                   data-bs-toggle="tooltip" 
                                                   title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($sale->status !== 'completed'): ?>
                                                <a href="<?= base_url('sales/edit/'.$sale->id) ?>" 
                                                   class="btn btn-warning btn-sm"
                                                   data-bs-toggle="tooltip" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button"
                                                   class="btn btn-danger btn-sm btn-delete"
                                                   data-id="<?= $sale->id ?>"
                                                   data-bs-toggle="tooltip" 
                                                   title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data penjualan</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Motor Sales Tab -->
                <div class="tab-pane fade" id="motor" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="motorTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="50" class="text-center">No</th>
                                    <th>No. Faktur</th>
                                    <th>Tanggal</th>
                                    <th>Customer</th>
                                    <th>Motor</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach ($sales as $sale): 
                                    $item_types = explode(',', $sale->item_types);
                                    if (in_array('motor', $item_types)):
                                ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= $sale->invoice_number ?? '-' ?></td>
                                    <td><?= date('d/m/Y', strtotime($sale->created_at)) ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-circle text-gray-500 me-2 fa-lg"></i>
                                            <div>
                                                <div class="fw-bold"><?= $sale->nama_customer ?></div>
                                                <small class="text-muted"><?= $sale->customer_phone ?? '' ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php 
                                        $items = explode('||', $sale->items_list);
                                        foreach ($items as $item) {
                                            if (strpos($item, 'motor:') !== false) {
                                                $motor = str_replace('motor:', '', $item);
                                                echo '<div class="badge bg-primary mb-1"><i class="fas fa-motorcycle me-1"></i>'.$motor.'</div><br>';
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="fw-bold">Rp <?= number_format($sale->total_amount, 0, ',', '.') ?></div>
                                        <small class="text-muted">
                                            <?= $payment_icons[$sale->payment_method] ?? ucfirst($sale->payment_method) ?>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <?= $status_badges[$sale->status] ?? ucfirst($sale->status) ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="<?= base_url('sales/view/'.$sale->id) ?>" 
                                               class="btn btn-info btn-sm"
                                               data-bs-toggle="tooltip" 
                                               title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($sale->status !== 'completed'): ?>
                                            <a href="<?= base_url('sales/edit/'.$sale->id) ?>" 
                                               class="btn btn-warning btn-sm"
                                               data-bs-toggle="tooltip" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                               class="btn btn-danger btn-sm btn-delete"
                                               data-id="<?= $sale->id ?>"
                                               data-bs-toggle="tooltip" 
                                               title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Sparepart Sales Tab -->
                <div class="tab-pane fade" id="sparepart" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="sparepartTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="50" class="text-center">No</th>
                                    <th>No. Faktur</th>
                                    <th>Tanggal</th>
                                    <th>Customer</th>
                                    <th>Sparepart</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach ($sales as $sale): 
                                    $item_types = explode(',', $sale->item_types);
                                    if (in_array('sparepart', $item_types)):
                                ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= $sale->invoice_number ?? '-' ?></td>
                                    <td><?= date('d/m/Y', strtotime($sale->created_at)) ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-circle text-gray-500 me-2 fa-lg"></i>
                                            <div>
                                                <div class="fw-bold"><?= $sale->nama_customer ?></div>
                                                <small class="text-muted"><?= $sale->customer_phone ?? '' ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php 
                                        $items = explode('||', $sale->items_list);
                                        foreach ($items as $item) {
                                            if (strpos($item, 'sparepart:') !== false) {
                                                $sparepart = str_replace('sparepart:', '', $item);
                                                echo '<div class="badge bg-info mb-1"><i class="fas fa-cogs me-1"></i>'.$sparepart.'</div><br>';
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="fw-bold">Rp <?= number_format($sale->total_amount, 0, ',', '.') ?></div>
                                        <small class="text-muted">
                                            <?= $payment_icons[$sale->payment_method] ?? ucfirst($sale->payment_method) ?>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <?= $status_badges[$sale->status] ?? ucfirst($sale->status) ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="<?= base_url('sales/view/'.$sale->id) ?>" 
                                               class="btn btn-info btn-sm"
                                               data-bs-toggle="tooltip" 
                                               title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($sale->status !== 'completed'): ?>
                                            <a href="<?= base_url('sales/edit/'.$sale->id) ?>" 
                                               class="btn btn-warning btn-sm"
                                               data-bs-toggle="tooltip" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                               class="btn btn-danger btn-sm btn-delete"
                                               data-id="<?= $sale->id ?>"
                                               data-bs-toggle="tooltip" 
                                               title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-1">Apakah Anda yakin ingin menghapus data penjualan ini?</p>
                <p class="text-danger mb-0"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Batal
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

<!-- DataTables JavaScript -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // DataTable Configuration
    const dataTableConfig = {
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json'
        },
        responsive: true,
        order: [[2, 'desc']], // Sort by date column descending
        columnDefs: [
            { orderable: false, targets: [7] }, // Disable sorting on action column
            { className: 'text-center', targets: [0, 6, 7] },
            { className: 'text-end', targets: [5] }
        ]
    };

    // Initialize DataTables
    $('#allTable').DataTable(dataTableConfig);
    $('#motorTable').DataTable(dataTableConfig);
    $('#sparepartTable').DataTable(dataTableConfig);

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            trigger: 'hover'
        });
    });

    // Handle delete confirmation
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        var saleId = $(this).data('id');
        $('#deleteForm').attr('action', '<?= base_url('sales/delete/') ?>' + saleId);
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    });

    // Auto close alerts after 3 seconds
    window.setTimeout(function() {
        $('.alert').fadeTo(500, 0).slideUp(500, function() {
            $(this).remove();
        });
    }, 3000);

    // Handle tab changes
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });
});
</script>

<style>
/* Table Styles */
.table > :not(caption) > * > * {
    padding: 0.75rem;
    vertical-align: middle;
}

.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}

/* Button Group Styles */
.btn-group > .btn {
    padding: 0.25rem 0.5rem;
}

.btn-group > .btn:not(:last-child) {
    margin-right: 1px;
}

/* DataTables Styles */
.dataTables_wrapper .dataTables_length select {
    padding-right: 2rem;
}

.dataTables_wrapper .dataTables_filter input {
    margin-left: 0.5rem;
}

/* Alert Styles */
.alert {
    margin-bottom: 1rem;
}

.alert-dismissible .btn-close {
    padding: 0.75rem 1rem;
}

/* Tab Styles */
.nav-tabs .nav-link {
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: #0d6efd;
    font-weight: 600;
}

/* Customer Info Styles */
.text-gray-500 {
    color: #adb5bd;
}

/* Badge Styles */
.badge {
    display: inline-block;
    margin-right: 0.5rem;
}

.badge i {
    font-size: 0.875rem;
}
</style>

<?php $this->load->view('templates/footer'); ?> 
