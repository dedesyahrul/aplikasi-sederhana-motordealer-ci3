<?php $this->load->view('templates/header'); ?>

<div class="row">
    <div class="col-xl-4">
        <!-- Customer Details Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Pelanggan</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="fw-bold">Nama Lengkap</label>
                    <p class="mb-0"><?= $customer->name ?></p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Nomor Telepon</label>
                    <p class="mb-0"><?= $customer->phone ?></p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Email</label>
                    <p class="mb-0"><?= $customer->email ?? '-' ?></p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Alamat</label>
                    <p class="mb-0"><?= $customer->address ?></p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Identitas</label>
                    <p class="mb-0"><?= strtoupper($customer->identity_type) ?> : <?= $customer->identity_number ?></p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Terdaftar Sejak</label>
                    <p class="mb-0"><?= date('d F Y', strtotime($customer->created_at)) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <!-- Purchase History -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Riwayat Pembelian</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No. Invoice</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($history['sales'])): ?>
                                <?php foreach ($history['sales'] as $sale): ?>
                                <tr>
                                    <td><?= $sale->invoice_number ?></td>
                                    <td><?= date('d/m/Y', strtotime($sale->created_at)) ?></td>
                                    <td>Rp <?= number_format($sale->total_amount, 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $sale->status == 'completed' ? 'success' : 
                                            ($sale->status == 'pending' ? 'warning' : 'danger') ?>">
                                            <?= ucfirst($sale->status) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('sales/view/'.$sale->id) ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada riwayat pembelian</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Service History -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Riwayat Servis</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No. Servis</th>
                                <th>Tanggal</th>
                                <th>Kendaraan</th>
                                <th>Total Biaya</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($history['services'])): ?>
                                <?php foreach ($history['services'] as $service): ?>
                                <tr>
                                    <td><?= $service->service_number ?></td>
                                    <td><?= date('d/m/Y', strtotime($service->created_at)) ?></td>
                                    <td><?= $service->vehicle_brand ?> <?= $service->vehicle_model ?></td>
                                    <td>Rp <?= number_format($service->total_cost, 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $service->status == 'completed' ? 'success' : 
                                            ($service->status == 'in_progress' ? 'info' : 
                                            ($service->status == 'pending' ? 'warning' : 'danger')) ?>">
                                            <?= ucfirst(str_replace('_', ' ', $service->status)) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('service/view/'.$service->id) ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada riwayat servis</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?> 
