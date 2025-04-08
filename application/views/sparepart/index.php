<?php $this->load->view('templates/header'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Sparepart</h2>
    <a href="<?= base_url('sparepart/add') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Sparepart
    </a>
</div>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $this->session->flashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
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
                    <?php if (isset($spareparts) && !empty($spareparts)): ?>
                        <?php foreach ($spareparts as $index => $sparepart): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= $sparepart->kode ?></td>
                                <td><?= $sparepart->nama ?></td>
                                <td><?= $sparepart->kategori ?></td>
                                <td>Rp <?= number_format($sparepart->harga, 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge <?= $sparepart->stok <= 5 ? 'bg-danger' : 'bg-success' ?>">
                                        <?= $sparepart->stok ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('sparepart/edit/'.$sparepart->id) ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= base_url('sparepart/delete/'.$sparepart->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data sparepart</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?> 
