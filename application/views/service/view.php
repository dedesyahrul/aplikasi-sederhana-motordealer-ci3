<?php $this->load->view('templates/header'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Servis</h5>
            <a href="<?= base_url('service') ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Informasi Servis</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">No. Servis</th>
                                    <td><?= $service->service_number ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td><?= date('d/m/Y', strtotime($service->created_at)) ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
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
                                </tr>
                                <tr>
                                    <th>Merk Kendaraan</th>
                                    <td><?= $service->vehicle_brand ?></td>
                                </tr>
                                <tr>
                                    <th>Model Kendaraan</th>
                                    <td><?= $service->vehicle_model ?></td>
                                </tr>
                                <tr>
                                    <th>Tahun Kendaraan</th>
                                    <td><?= $service->vehicle_year ?></td>
                                </tr>
                                <tr>
                                    <th>Nomor Kendaraan</th>
                                    <td><?= $service->vehicle_number ?></td>
                                </tr>
                                <tr>
                                    <th>Keluhan</th>
                                    <td><?= $service->complaints ?></td>
                                </tr>
                                <?php if ($service->diagnosis) : ?>
                                    <tr>
                                        <th>Diagnosis</th>
                                        <td><?= $service->diagnosis ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if ($service->mechanic_notes) : ?>
                                    <tr>
                                        <th>Catatan Mekanik</th>
                                        <td><?= $service->mechanic_notes ?></td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Informasi Customer</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Nama</th>
                                    <td><?= $service->customer_name ?></td>
                                </tr>
                                <tr>
                                    <th>No. Telepon</th>
                                    <td><?= $service->customer_phone ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?= $service->customer_email ?: '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td><?= $service->customer_address ?: '-' ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Sparepart yang Digunakan</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($service_items)) : ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Sparepart</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total_parts = 0;
                                    foreach ($service_items as $index => $item) : 
                                        $total_parts += $item->subtotal;
                                    ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= $item->sparepart_name ?></td>
                                            <td>Rp <?= number_format($item->price, 0, ',', '.') ?></td>
                                            <td><?= $item->quantity ?></td>
                                            <td>Rp <?= number_format($item->subtotal, 0, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Total Sparepart</th>
                                        <th>Rp <?= number_format($total_parts, 0, ',', '.') ?></th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right">Biaya Service</th>
                                        <th>Rp <?= number_format($service->service_cost, 0, ',', '.') ?></th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right">Total Biaya</th>
                                        <th>Rp <?= number_format($service->total_cost, 0, ',', '.') ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-info">
                            Tidak ada sparepart yang digunakan
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?> 
