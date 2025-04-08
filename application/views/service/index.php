<?php $this->load->view('templates/header'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Servis</h5>
            <a href="<?= base_url('service/add') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Servis
            </a>
        </div>
        <div class="card-body">
            <?php if ($this->session->flashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Servis</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Kendaraan</th>
                            <th>Total Biaya</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($services)) : ?>
                            <?php foreach ($services as $index => $service) : ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= $service->service_number ?></td>
                                    <td><?= date('d/m/Y', strtotime($service->created_at)) ?></td>
                                    <td><?= $service->customer_name ?></td>
                                    <td><?= $service->vehicle_brand . ' ' . $service->vehicle_model . ' (' . $service->vehicle_year . ')' ?></td>
                                    <td>Rp <?= number_format($service->total_cost, 0, ',', '.') ?></td>
                                    <td>
                                        <?php
                                        $status_class = '';
                                        $status_text = '';
                                        switch ($service->status) {
                                            case 'completed':
                                                $status_class = 'success';
                                                $status_text = 'Selesai';
                                                break;
                                            case 'in_progress':
                                                $status_class = 'warning';
                                                $status_text = 'Proses';
                                                break;
                                            default:
                                                $status_class = 'secondary';
                                                $status_text = 'Pending';
                                        }
                                        ?>
                                        <span class="badge badge-<?= $status_class ?>"><?= $status_text ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <?php if ($service->status != 'completed') : ?>
                                                <form action="<?= base_url('service/update_status/' . $service->id) ?>" method="post" class="d-inline">
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Apakah Anda yakin ingin menyelesaikan servis ini?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            <a href="<?= base_url('service/view/' . $service->id) ?>" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('service/edit/' . $service->id) ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?= base_url('service/delete/' . $service->id) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data servis</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?> 
