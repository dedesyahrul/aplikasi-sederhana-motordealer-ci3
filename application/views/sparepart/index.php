<?php $this->load->view('templates/header'); ?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Sparepart</h1>
        <a href="<?= base_url('sparepart/add') ?>" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus fa-sm"></i> Tambah Sparepart
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

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($spareparts as $sparepart): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $sparepart->kode ?></td>
                            <td><?= $sparepart->nama ?></td>
                            <td><?= $sparepart->kategori ?></td>
                            <td>Rp <?= number_format($sparepart->harga, 0, ',', '.') ?></td>
                            <td>
                                <span class="badge bg-<?= $sparepart->stok <= 5 ? 'danger' : 'success' ?>">
                                    <?= $sparepart->stok ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url('sparepart/edit/'.$sparepart->id) ?>" 
                                       class="btn btn-warning btn-sm" 
                                       data-toggle="tooltip" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('sparepart/delete/'.$sparepart->id) ?>" 
                                       class="btn btn-danger btn-sm btn-delete" 
                                       data-toggle="tooltip" 
                                       title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
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
                <p>Apakah Anda yakin ingin menghapus sparepart ini?</p>
                <p class="text-warning"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a href="#" class="btn btn-danger" id="confirmDelete">Hapus</a>
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
        }
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Handle delete confirmation
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        var deleteUrl = $(this).attr('href');
        $('#confirmDelete').attr('href', deleteUrl);
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
