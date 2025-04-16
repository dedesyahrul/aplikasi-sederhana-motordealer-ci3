<?php $this->load->view('templates/header'); ?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Penjualan Motor</h1>
        <a href="<?= base_url('sales/add') ?>" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus fa-sm"></i> Tambah Penjualan
        </a>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-1"></i>
        <?= $this->session->flashdata('success'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-1"></i>
        <?= $this->session->flashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>No. Faktur</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Motor</th>
                            <th>Harga Jual</th>
                            <th>Status</th>
                            <th class="text-center" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($sales) && !empty($sales)): ?>
                            <?php $no = 1; foreach ($sales as $sale): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $sale->invoice_number ?? '-' ?></td>
                                <td><?= date('d/m/Y', strtotime($sale->created_at)) ?></td>
                                <td><?= $sale->nama_customer ?></td>
                                <td><?= $sale->motor_name ?? '-' ?></td>
                                <td>Rp <?= number_format($sale->total_amount, 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge badge-<?= strtolower($sale->status) == 'completed' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($sale->status) ?? 'Pending' ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="<?= base_url('sales/view/'.$sale->id) ?>" 
                                           class="btn btn-info btn-sm"
                                           data-toggle="tooltip" 
                                           title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('sales/edit/'.$sale->id) ?>" 
                                           class="btn btn-warning btn-sm"
                                           data-toggle="tooltip" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)" 
                                           class="btn btn-danger btn-sm btn-delete"
                                           data-id="<?= $sale->id ?>"
                                           data-toggle="tooltip" 
                                           title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
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
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data penjualan ini?</p>
                <p class="text-warning"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#dataTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        },
        "responsive": true,
        "order": [[2, "desc"]] // Sort by date column descending
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Handle delete confirmation
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        var saleId = $(this).data('id');
        $('#deleteForm').attr('action', '<?= base_url('sales/delete/') ?>' + saleId);
        $('#deleteModal').modal('show');
    });

    // Auto close alerts
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 3000);
});
</script>

<?php $this->load->view('templates/footer'); ?> 
