<?php $this->load->view('templates/header'); ?>

<div class="card">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Edit Data Penjualan</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('sales/update/' . $sale->id) ?>" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Customer</label>
                        <select class="form-select <?= form_error('customer_id') ? 'is-invalid' : '' ?>" 
                                id="customer_id" name="customer_id">
                            <option value="">Pilih Customer</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer->id ?>" <?= set_select('customer_id', $customer->id, ($customer->id == $sale->customer_id)) ?>>
                                    <?= $customer->name ?> - <?= $customer->phone ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('customer_id', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <div class="mb-3">
                        <label for="motor_id" class="form-label">Motor</label>
                        <select class="form-select <?= form_error('motor_id') ? 'is-invalid' : '' ?>" 
                                id="motor_id" name="motor_id">
                            <option value="">Pilih Motor</option>
                            <?php foreach ($motors as $motor): ?>
                                <option value="<?= $motor->id ?>" <?= set_select('motor_id', $motor->id, ($motor->id == $sale->motor_id)) ?>>
                                    <?= $motor->merk ?> <?= $motor->model ?> - Rp <?= number_format($motor->harga, 0, ',', '.') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('motor_id', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_jual" class="form-label">Tanggal Penjualan</label>
                        <input type="date" class="form-control <?= form_error('tanggal_jual') ? 'is-invalid' : '' ?>" 
                            id="tanggal_jual" name="tanggal_jual" value="<?= set_value('tanggal_jual', date('Y-m-d', strtotime($sale->created_at))) ?>">
                        <?= form_error('tanggal_jual', '<div class="invalid-feedback">', '</div>') ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="harga_jual" class="form-label">Harga Jual</label>
                        <input type="number" class="form-control <?= form_error('harga_jual') ? 'is-invalid' : '' ?>" 
                            id="harga_jual" name="harga_jual" value="<?= set_value('harga_jual', $sale->total_amount) ?>">
                        <?= form_error('harga_jual', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <div class="mb-3">
                        <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                        <select class="form-select <?= form_error('metode_pembayaran') ? 'is-invalid' : '' ?>" 
                                id="metode_pembayaran" name="metode_pembayaran">
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="Cash" <?= set_select('metode_pembayaran', 'Cash', ($sale->payment_method == 'Cash')) ?>>Cash</option>
                            <option value="Credit Card" <?= set_select('metode_pembayaran', 'Credit Card', ($sale->payment_method == 'Credit Card')) ?>>Credit Card</option>
                            <option value="Debit" <?= set_select('metode_pembayaran', 'Debit', ($sale->payment_method == 'Debit')) ?>>Debit</option>
                            <option value="Transfer" <?= set_select('metode_pembayaran', 'Transfer', ($sale->payment_method == 'Transfer')) ?>>Transfer</option>
                        </select>
                        <?= form_error('metode_pembayaran', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?= set_value('keterangan', $sale->notes) ?></textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <a href="<?= base_url('sales') ?>" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize select2
    $('.form-select').select2({
        theme: 'bootstrap4'
    });

    // Handle motor selection to update price
    $('#motor_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        if (selectedOption.val()) {
            var price = selectedOption.text().split('Rp ')[1].replace(/\./g, '');
            $('#harga_jual').val(price);
        }
    });
});
</script>

<?php $this->load->view('templates/footer'); ?> 
