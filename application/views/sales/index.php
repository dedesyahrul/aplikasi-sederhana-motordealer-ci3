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
                        <table class="table table-bordered datatable" width="100%">
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
                                        <td><?= $sale->created_at ?></td>
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
                                            <?php if (!empty($sale->items)): ?>
                                                <?php foreach ($sale->items as $item): ?>
                                                    <?php if ($item->item_type == 'motor'): ?>
                                                        <div class="badge bg-primary mb-1">
                                                            <i class="fas fa-motorcycle me-1"></i>
                                                            <?= $item->nama_item ?>
                                                        </div><br>
                                                    <?php else: ?>
                                                        <div class="badge bg-info mb-1">
                                                            <i class="fas fa-cogs me-1"></i>
                                                            <?= $item->nama_item ?> (<?= $item->quantity ?> unit)
                                                        </div><br>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="text-muted">Tidak ada item</span>
                                            <?php endif; ?>
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
                                                <?php if ($sale->status == 'pending'): ?>
                                                <a href="<?= base_url('sales/confirm_payment/'.$sale->id) ?>"
                                                   class="btn btn-success btn-sm btn-confirm-payment"
                                                   data-bs-toggle="tooltip"
                                                   title="Konfirmasi Pembayaran">
                                                    <i class="fas fa-check-circle"></i>
                                                </a>
                                                <?php endif; ?>
                                                <?php if ($sale->status !== 'completed'): ?>
                                                <a href="<?= base_url('sales/edit/'.$sale->id) ?>" 
                                                   class="btn btn-warning btn-sm"
                                                   data-bs-toggle="tooltip" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?= base_url('sales/delete/'.$sale->id) ?>"
                                                   class="btn btn-danger btn-sm btn-delete"
                                                   data-bs-toggle="tooltip" 
                                                   title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </a>
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
                        <table class="table table-bordered datatable" width="100%">
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
                                    if (in_array('motor', $sale->item_types)):
                                ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= $sale->invoice_number ?? '-' ?></td>
                                    <td><?= $sale->created_at ?></td>
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
                                        <?php if (!empty($sale->items)): ?>
                                            <?php foreach ($sale->items as $item): ?>
                                                <?php if ($item->item_type == 'motor'): ?>
                                                    <div class="badge bg-primary mb-1">
                                                        <i class="fas fa-motorcycle me-1"></i>
                                                        <?= $item->nama_item ?>
                                                    </div><br>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Tidak ada motor</span>
                                        <?php endif; ?>
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
                                            <?php if ($sale->status == 'pending'): ?>
                                            <a href="<?= base_url('sales/confirm_payment/'.$sale->id) ?>"
                                               class="btn btn-success btn-sm btn-confirm-payment"
                                               data-bs-toggle="tooltip"
                                               title="Konfirmasi Pembayaran">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                            <?php endif; ?>
                                            <?php if ($sale->status !== 'completed'): ?>
                                            <a href="<?= base_url('sales/edit/'.$sale->id) ?>" 
                                               class="btn btn-warning btn-sm"
                                               data-bs-toggle="tooltip" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('sales/delete/'.$sale->id) ?>"
                                               class="btn btn-danger btn-sm btn-delete"
                                               data-bs-toggle="tooltip" 
                                               title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </a>
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
                        <table class="table table-bordered datatable" width="100%">
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
                                    if (in_array('sparepart', $sale->item_types)):
                                ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= $sale->invoice_number ?? '-' ?></td>
                                    <td><?= $sale->created_at ?></td>
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
                                        <?php if (!empty($sale->items)): ?>
                                            <?php foreach ($sale->items as $item): ?>
                                                <?php if ($item->item_type == 'sparepart'): ?>
                                                    <div class="badge bg-info mb-1">
                                                        <i class="fas fa-cogs me-1"></i>
                                                        <?= $item->nama_item ?> (<?= $item->quantity ?> unit)
                                                    </div><br>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Tidak ada sparepart</span>
                                        <?php endif; ?>
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
                                            <?php if ($sale->status == 'pending'): ?>
                                            <a href="<?= base_url('sales/confirm_payment/'.$sale->id) ?>"
                                               class="btn btn-success btn-sm btn-confirm-payment"
                                               data-bs-toggle="tooltip"
                                               title="Konfirmasi Pembayaran">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                            <?php endif; ?>
                                            <?php if ($sale->status !== 'completed'): ?>
                                            <a href="<?= base_url('sales/edit/'.$sale->id) ?>" 
                                               class="btn btn-warning btn-sm"
                                               data-bs-toggle="tooltip" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('sales/delete/'.$sale->id) ?>"
                                               class="btn btn-danger btn-sm btn-delete"
                                               data-bs-toggle="tooltip" 
                                               title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </a>
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

<!-- Payment Confirmation Modal -->
<div class="modal fade" id="confirmPaymentModal" tabindex="-1" aria-labelledby="confirmPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmPaymentModalLabel">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    Konfirmasi Pembayaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="confirmPaymentForm" method="POST">
                <div class="modal-body">
                    <p class="mb-1">Apakah Anda yakin ingin mengkonfirmasi pembayaran ini?</p>
                    <p class="text-success mb-0"><small>Status penjualan akan diubah menjadi "Selesai".</small></p>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete confirmation
    document.querySelectorAll('.btn-delete').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            var saleId = this.getAttribute('href').split('/').pop();
            document.getElementById('deleteForm').setAttribute('action', '<?= base_url('sales/delete/') ?>' + saleId);
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        });
    });

    // Handle payment confirmation
    document.querySelectorAll('.btn-confirm-payment').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            var saleId = this.getAttribute('href').split('/').pop();
            document.getElementById('confirmPaymentForm').setAttribute('action', '<?= base_url('sales/confirm_payment/') ?>' + saleId);
            var confirmPaymentModal = new bootstrap.Modal(document.getElementById('confirmPaymentModal'));
            confirmPaymentModal.show();
        });
    });

    // Handle tab changes
    document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(function(tab) {
        tab.addEventListener('shown.bs.tab', function(e) {
            if ($.fn.dataTable) {
                $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
            }
        });
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

/* Badge Styles */
.badge {
    display: inline-block;
    margin-right: 0.5rem;
}

.badge i {
    font-size: 0.875rem;
}

/* Modal Styles */
.modal-dialog {
    margin: 1.75rem auto;
}

.modal-content {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-header {
    border-bottom: 1px solid #e3e6f0;
}

.modal-footer {
    border-top: 1px solid #e3e6f0;
}
</style>

<?php $this->load->view('templates/footer'); ?> 
