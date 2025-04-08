<?php $this->load->view('templates/header'); ?>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Detail Penjualan</h6>
            <a href="<?= base_url('sales') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5 class="mb-4">Informasi Penjualan</h5>
                <table class="table">
                    <tr>
                        <th width="30%">Tanggal</th>
                        <td><?= date('d/m/Y', strtotime($sale->tanggal_jual)) ?></td>
                    </tr>
                    <tr>
                        <th>Harga Jual</th>
                        <td>Rp <?= number_format($sale->harga_jual, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <td><?= $sale->metode_pembayaran ?></td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td><?= $sale->keterangan ?: '-' ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5 class="mb-4">Informasi Customer</h5>
                <table class="table">
                    <tr>
                        <th width="30%">Nama</th>
                        <td><?= $sale->nama_customer ?></td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td><?= $sale->no_telp_customer ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= $sale->email_customer ?: '-' ?></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td><?= $sale->alamat_customer ?: '-' ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <h5 class="mb-4">Informasi Motor</h5>
                <table class="table">
                    <tr>
                        <th width="15%">Merk</th>
                        <td><?= $sale->merk_motor ?></td>
                    </tr>
                    <tr>
                        <th>Model</th>
                        <td><?= $sale->model_motor ?></td>
                    </tr>
                    <tr>
                        <th>Tahun</th>
                        <td><?= $sale->tahun_motor ?></td>
                    </tr>
                    <tr>
                        <th>Warna</th>
                        <td><?= $sale->warna_motor ?></td>
                    </tr>
                    <tr>
                        <th>Harga Motor</th>
                        <td>Rp <?= number_format($sale->harga_motor, 0, ',', '.') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?> 
