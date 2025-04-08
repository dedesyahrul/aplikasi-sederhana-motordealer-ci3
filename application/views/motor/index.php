<?php $this->load->view('templates/header'); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Motor</h6>
        <a href="<?= base_url('motor/add') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Motor
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered datatable" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Merk</th>
                        <th>Model</th>
                        <th>Tahun</th>
                        <th>Warna</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($motors as $motor): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $motor->merk ?></td>
                        <td><?= $motor->model ?></td>
                        <td><?= $motor->tahun ?></td>
                        <td><?= $motor->warna ?></td>
                        <td>Rp <?= number_format($motor->harga, 0, ',', '.') ?></td>
                        <td>
                            <span class="badge bg-<?= $motor->stok > 0 ? 'success' : 'danger' ?>">
                                <?= $motor->stok ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="<?= base_url('motor/edit/'.$motor->id) ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('motor/delete/'.$motor->id) ?>" class="btn btn-danger btn-sm btn-delete">
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

<?php $this->load->view('templates/footer'); ?>
