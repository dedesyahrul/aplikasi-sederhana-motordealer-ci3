<?php $this->load->view('templates/header'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Pelanggan</h6>
        <a href="<?= base_url('customer/add') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Pelanggan
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered datatable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>No. Telepon</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th>Identitas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $customer->name ?></td>
                        <td><?= $customer->phone ?></td>
                        <td><?= $customer->email ?? '-' ?></td>
                        <td><?= $customer->address ?></td>
                        <td>
                            <?= strtoupper($customer->identity_type) ?> : <?= $customer->identity_number ?>
                        </td>
                        <td>
                            <a href="<?= base_url('customer/view/'.$customer->id) ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?= base_url('customer/edit/'.$customer->id) ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= base_url('customer/delete/'.$customer->id) ?>" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?> 
