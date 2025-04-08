<?php $this->load->view('templates/header'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Data Servis</h5>
        </div>
        <div class="card-body">
            <?php if (validation_errors()) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= validation_errors() ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('service/update/' . $service->id) ?>" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="customer_id">Customer <span class="text-danger">*</span></label>
                            <select name="customer_id" id="customer_id" class="form-control <?= form_error('customer_id') ? 'is-invalid' : '' ?>" required>
                                <option value="">Pilih Customer</option>
                                <?php foreach ($customers as $customer) : ?>
                                    <option value="<?= $customer->id ?>" <?= set_select('customer_id', $customer->id, ($service->customer_id == $customer->id)) ?>>
                                        <?= $customer->name ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= form_error('customer_id') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="vehicle_brand">Merk Kendaraan <span class="text-danger">*</span></label>
                            <input type="text" name="vehicle_brand" id="vehicle_brand" class="form-control <?= form_error('vehicle_brand') ? 'is-invalid' : '' ?>" value="<?= set_value('vehicle_brand', $service->vehicle_brand) ?>" required>
                            <div class="invalid-feedback">
                                <?= form_error('vehicle_brand') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="vehicle_model">Model Kendaraan <span class="text-danger">*</span></label>
                            <input type="text" name="vehicle_model" id="vehicle_model" class="form-control <?= form_error('vehicle_model') ? 'is-invalid' : '' ?>" value="<?= set_value('vehicle_model', $service->vehicle_model) ?>" required>
                            <div class="invalid-feedback">
                                <?= form_error('vehicle_model') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="vehicle_year">Tahun Kendaraan <span class="text-danger">*</span></label>
                            <input type="number" name="vehicle_year" id="vehicle_year" class="form-control <?= form_error('vehicle_year') ? 'is-invalid' : '' ?>" value="<?= set_value('vehicle_year', $service->vehicle_year) ?>" required>
                            <div class="invalid-feedback">
                                <?= form_error('vehicle_year') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="vehicle_number">Nomor Kendaraan <span class="text-danger">*</span></label>
                            <input type="text" name="vehicle_number" id="vehicle_number" class="form-control <?= form_error('vehicle_number') ? 'is-invalid' : '' ?>" value="<?= set_value('vehicle_number', $service->vehicle_number) ?>" required>
                            <div class="invalid-feedback">
                                <?= form_error('vehicle_number') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="complaints">Keluhan <span class="text-danger">*</span></label>
                            <textarea name="complaints" id="complaints" rows="3" class="form-control <?= form_error('complaints') ? 'is-invalid' : '' ?>" required><?= set_value('complaints', $service->complaints) ?></textarea>
                            <div class="invalid-feedback">
                                <?= form_error('complaints') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="diagnosis">Diagnosis</label>
                            <textarea name="diagnosis" id="diagnosis" rows="3" class="form-control <?= form_error('diagnosis') ? 'is-invalid' : '' ?>"><?= set_value('diagnosis', $service->diagnosis) ?></textarea>
                            <div class="invalid-feedback">
                                <?= form_error('diagnosis') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="service_cost">Biaya Service <span class="text-danger">*</span></label>
                            <input type="number" name="service_cost" id="service_cost" class="form-control <?= form_error('service_cost') ? 'is-invalid' : '' ?>" value="<?= set_value('service_cost', $service->service_cost) ?>" required>
                            <div class="invalid-feedback">
                                <?= form_error('service_cost') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control <?= form_error('status') ? 'is-invalid' : '' ?>" required>
                                <option value="pending" <?= set_select('status', 'pending', ($service->status == 'pending')) ?>>Pending</option>
                                <option value="in_progress" <?= set_select('status', 'in_progress', ($service->status == 'in_progress')) ?>>Proses</option>
                                <option value="completed" <?= set_select('status', 'completed', ($service->status == 'completed')) ?>>Selesai</option>
                            </select>
                            <div class="invalid-feedback">
                                <?= form_error('status') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="mechanic_notes">Catatan Mekanik</label>
                            <textarea name="mechanic_notes" id="mechanic_notes" rows="3" class="form-control <?= form_error('mechanic_notes') ? 'is-invalid' : '' ?>"><?= set_value('mechanic_notes', $service->mechanic_notes) ?></textarea>
                            <div class="invalid-feedback">
                                <?= form_error('mechanic_notes') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Sparepart yang Digunakan</h6>
                        <button type="button" class="btn btn-primary btn-sm" id="add_sparepart">
                            <i class="fas fa-plus"></i> Tambah Sparepart
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="sparepart_container">
                            <?php if (!empty($service_items)) : ?>
                                <?php foreach ($service_items as $item) : ?>
                                    <div class="row sparepart-row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Sparepart</label>
                                                <select name="sparepart_id[]" class="form-control sparepart-select" required>
                                                    <option value="">Pilih Sparepart</option>
                                                    <?php foreach ($spareparts as $sparepart) : ?>
                                                        <option value="<?= $sparepart->id ?>" data-price="<?= $sparepart->harga ?>" <?= ($item->sparepart_id == $sparepart->id) ? 'selected' : '' ?>>
                                                            <?= $sparepart->nama ?> - Rp <?= number_format($sparepart->harga, 0, ',', '.') ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Jumlah</label>
                                                <input type="number" name="quantity[]" class="form-control quantity" min="1" value="<?= $item->quantity ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Subtotal</label>
                                                <input type="text" class="form-control subtotal" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-block remove-sparepart">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <div class="row sparepart-row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Sparepart</label>
                                            <select name="sparepart_id[]" class="form-control sparepart-select" required>
                                                <option value="">Pilih Sparepart</option>
                                                <?php foreach ($spareparts as $sparepart) : ?>
                                                    <option value="<?= $sparepart->id ?>" data-price="<?= $sparepart->harga ?>">
                                                        <?= $sparepart->nama ?> - Rp <?= number_format($sparepart->harga, 0, ',', '.') ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Jumlah</label>
                                            <input type="number" name="quantity[]" class="form-control quantity" min="1" value="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Subtotal</label>
                                            <input type="text" class="form-control subtotal" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="button" class="btn btn-danger btn-block remove-sparepart">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= base_url('service') ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Function to calculate subtotal
    function calculateSubtotal(row) {
        var select = row.find('.sparepart-select');
        var quantity = row.find('.quantity').val();
        var price = select.find(':selected').data('price') || 0;
        var subtotal = price * quantity;
        row.find('.subtotal').val('Rp ' + subtotal.toLocaleString('id-ID'));
    }

    // Calculate initial subtotals
    $('.sparepart-row').each(function() {
        calculateSubtotal($(this));
    });

    // Add new sparepart row
    $('#add_sparepart').click(function() {
        var newRow = $('.sparepart-row:first').clone();
        newRow.find('input').val('');
        newRow.find('select').val('');
        $('#sparepart_container').append(newRow);
    });

    // Remove sparepart row
    $(document).on('click', '.remove-sparepart', function() {
        if ($('.sparepart-row').length > 1) {
            $(this).closest('.sparepart-row').remove();
        }
    });

    // Recalculate subtotal when sparepart or quantity changes
    $(document).on('change', '.sparepart-select, .quantity', function() {
        calculateSubtotal($(this).closest('.sparepart-row'));
    });
});
</script>

<?php $this->load->view('templates/footer'); ?> 
