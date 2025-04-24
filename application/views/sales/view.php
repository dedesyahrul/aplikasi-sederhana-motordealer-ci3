<?php $this->load->view('templates/header'); ?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Penjualan</h1>
        <a href="<?= base_url('sales') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-primary mb-3">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-file-invoice me-2"></i>Informasi Penjualan
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="35%">No. Faktur</th>
                                    <td><?= $sale->invoice_number ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td><?= date('d/m/Y H:i', strtotime($sale->created_at)) ?></td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td>Rp <?= number_format($sale->total_amount, 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <th>Metode Pembayaran</th>
                                    <td>
                                        <?php
                                        $payment_labels = [
                                            'cash' => '<i class="fas fa-money-bill-wave me-1"></i>Tunai',
                                            'transfer' => '<i class="fas fa-university me-1"></i>Transfer Bank',
                                            'credit_card' => '<i class="fas fa-credit-card me-1"></i>Kartu Kredit'
                                        ];
                                        echo $payment_labels[$sale->payment_method] ?? ucfirst($sale->payment_method);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <?php
                                        $status_badge = [
                                            'pending' => 'warning',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        $status_label = [
                                            'pending' => 'Menunggu',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Dibatalkan'
                                        ];
                                        ?>
                                        <span class="badge bg-<?= $status_badge[$sale->status] ?>">
                                            <?= $status_label[$sale->status] ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td><?= $sale->notes ?: '<span class="text-muted">-</span>' ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-info mb-3">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-user me-2"></i>Informasi Customer
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="35%">Nama</th>
                                    <td><?= $sale->customer_name ?></td>
                                </tr>
                                <tr>
                                    <th>No. Telepon</th>
                                    <td>
                                        <i class="fas fa-phone me-1"></i>
                                        <?= $sale->customer_phone ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>
                                        <?php if (!empty($sale->customer_email)): ?>
                                            <i class="fas fa-envelope me-1"></i>
                                            <?= $sale->customer_email ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        <?= $sale->customer_address ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Identitas</th>
                                    <td>
                                        <i class="fas fa-id-card me-1"></i>
                                        <?php
                                        $identity_types = [
                                            'ktp' => 'KTP',
                                            'sim' => 'SIM',
                                            'passport' => 'Passport'
                                        ];
                                        echo isset($identity_types[$sale->identity_type]) ? $identity_types[$sale->identity_type] : ucfirst($sale->identity_type);
                                        ?> - 
                                        <?= $sale->identity_number ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-success mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>Item Penjualan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="50" class="text-center">No</th>
                                    <th width="100">Tipe</th>
                                    <th>Item</th>
                                    <th class="text-center" width="100">Jumlah</th>
                                    <th class="text-end" width="150">Harga</th>
                                    <th class="text-end" width="150">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1; 
                                $total = 0;
                                foreach ($sale_items as $item): 
                                    $total += $item->subtotal;
                                ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td>
                                        <?php
                                        $type_labels = [
                                            'motor' => '<span class="badge bg-primary"><i class="fas fa-motorcycle me-1"></i>Motor</span>',
                                            'sparepart' => '<span class="badge bg-info"><i class="fas fa-cogs me-1"></i>Sparepart</span>'
                                        ];
                                        echo $type_labels[$item->item_type] ?? ucfirst($item->item_type);
                                        ?>
                                    </td>
                                    <td><?= $item->item_name ?></td>
                                    <td class="text-center"><?= $item->quantity ?></td>
                                    <td class="text-end">Rp <?= number_format($item->price, 0, ',', '.') ?></td>
                                    <td class="text-end">Rp <?= number_format($item->subtotal, 0, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="5" class="text-end">Total:</td>
                                    <td class="text-end">Rp <?= number_format($total, 0, ',', '.') ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <?php if ($sale->status == 'completed'): ?>
            <div class="alert alert-success d-flex align-items-center mb-0">
                <i class="fas fa-check-circle me-2 fs-5"></i>
                <div>
                    Penjualan ini telah selesai pada <?= date('d/m/Y H:i', strtotime($sale->updated_at)) ?>
                </div>
            </div>
            <?php elseif ($sale->status == 'pending'): ?>
            <div class="alert alert-warning d-flex align-items-center mb-0">
                <i class="fas fa-clock me-2 fs-5"></i>
                <div>
                    Menunggu pembayaran
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Card Styles */
.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header {
    padding: 1rem;
}

.table > :not(caption) > * > * {
    padding: 0.75rem;
    vertical-align: middle;
}

.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}

.alert {
    margin-bottom: 1rem;
}

.table-borderless > :not(caption) > * > * {
    border-bottom-width: 0;
}

.text-muted {
    color: #6c757d !important;
}
</style>

<?php $this->load->view('templates/footer'); ?> 
