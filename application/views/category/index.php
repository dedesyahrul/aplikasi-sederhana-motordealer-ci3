<?php $this->load->view('templates/header'); ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Kategori</h1>
        <a href="<?= base_url('category/add') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
    </div>

    <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Jumlah Item</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($categories as $category): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $category->name ?></td>
                            <td><?= $category->description ?></td>
                            <td>
                                <span class="badge bg-info">
                                    <?= $category->item_count ?> item
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url('category/edit/'.$category->id) ?>" 
                                       class="btn btn-warning btn-sm" 
                                       data-toggle="tooltip" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('category/delete/'.$category->id) ?>" 
                                       class="btn btn-danger btn-sm btn-delete" 
                                       data-toggle="tooltip" 
                                       title="Hapus"
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
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

<script>
$(document).ready(function() {
    $('#dataTable').DataTable();
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Auto close alerts
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 3000);
});
</script>

<?php $this->load->view('templates/footer'); ?> 
