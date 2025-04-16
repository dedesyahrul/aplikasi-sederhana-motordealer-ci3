<?php $this->load->view('templates/header'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Detail Penjualan</h6>
                <a href="<?= base_url('sales') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-4">Informasi Penjualan</h5>
                    <table class="table">
                        <tr>
                            <th width="30%">No. Faktur</th>
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
                                    'cash' => 'Tunai',
                                    'transfer' => 'Transfer Bank',
                                    'credit_card' => 'Kartu Kredit'
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
                            <td><?= $sale->notes ?: '-' ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-4">Informasi Customer</h5>
                    <table class="table">
                        <tr>
                            <th width="30%">Nama</th>
                            <td><?= $customer->name ?></td>
                        </tr>
                        <tr>
                            <th>No. Telepon</th>
                            <td><?= $customer->phone ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?= $customer->email ?: '-' ?></td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td><?= $customer->address ?></td>
                        </tr>
                        <tr>
                            <th>Identitas</th>
                            <td>
                                <?php
                                $identity_types = [
                                    'ktp' => 'KTP',
                                    'sim' => 'SIM',
                                    'passport' => 'Passport'
                                ];
                                echo $identity_types[$customer->identity_type] ?? ucfirst($customer->identity_type);
                                ?> - 
                                <?= $customer->identity_number ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h5 class="mb-4">Item Penjualan</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tipe</th>
                                    <th>Item</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
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
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <?php
                                        $type_labels = [
                                            'motor' => 'Motor',
                                            'sparepart' => 'Sparepart'
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
                                <tr>
                                    <th colspan="5" class="text-end">Total:</th>
                                    <th class="text-end">Rp <?= number_format($total, 0, ',', '.') ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <?php if ($sale->status == 'completed'): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle me-2"></i> Penjualan ini telah selesai pada <?= date('d/m/Y H:i', strtotime($sale->updated_at)) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?> 
