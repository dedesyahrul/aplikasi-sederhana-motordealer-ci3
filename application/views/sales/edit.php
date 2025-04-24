<?php $this->load->view('templates/header'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Edit Penjualan</h6>
            <a href="<?= base_url('sales') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
    </div>
    <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs mb-3" id="salesTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= isset($sale_items[0]) && $sale_items[0]->item_type == 'motor' ? 'active' : '' ?>" 
                            id="motor-tab" data-bs-toggle="tab" data-bs-target="#motor" type="button" role="tab">
                        <i class="fas fa-motorcycle me-2"></i>Penjualan Motor
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= isset($sale_items[0]) && $sale_items[0]->item_type == 'sparepart' ? 'active' : '' ?>" 
                            id="sparepart-tab" data-bs-toggle="tab" data-bs-target="#sparepart" type="button" role="tab">
                        <i class="fas fa-cogs me-2"></i>Penjualan Sparepart
                    </button>
                </li>
            </ul>

            <!-- Tab content -->
            <div class="tab-content" id="salesTabContent">
                <!-- Motor Sales Tab -->
                <div class="tab-pane fade <?= isset($sale_items[0]) && $sale_items[0]->item_type == 'motor' ? 'show active' : '' ?>" 
                     id="motor" role="tabpanel">
                    <form action="<?= base_url('sales/update/' . $sale->id) ?>" method="post" id="motorForm">
                        <input type="hidden" name="sale_type" value="motor">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="motor_customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="motor_customer_id" name="customer_id" required>
                                        <option value="">Pilih Customer</option>
                                        <?php foreach ($customers as $customer): ?>
                                            <option value="<?= $customer->id ?>" <?= $customer->id == $sale->customer_name ? 'selected' : '' ?>>
                                                <?= $customer->name ?> - <?= $customer->phone ?> (<?= $customer->identity_number ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="motor_id" class="form-label">Motor <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="motor_id" name="items[]" required>
                                        <option value="">Pilih Motor</option>
                                        <?php foreach ($motors as $motor): ?>
                                            <?php 
                                            $selected = false;
                                            foreach ($sale_items as $item) {
                                                if ($item->item_type == 'motor' && $item->item_id == $motor->id) {
                                                    $selected = true;
                                                    break;
                                                }
                                            }
                                            ?>
                                            <option value="motor_<?= $motor->id ?>" 
                                                    data-price="<?= $motor->harga ?>"
                                                    data-stok="<?= $motor->stok ?>"
                                                    <?= $selected ? 'selected' : '' ?>>
                                                <?= $motor->merk ?> <?= $motor->model ?> <?= $motor->tahun ?> - <?= $motor->warna ?> 
                                                (Stok: <?= $motor->stok ?>) - Rp <?= number_format($motor->harga, 0, ',', '.') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <input type="hidden" name="quantities[]" value="1">

                                <div class="mb-3">
                                    <label for="motor_payment" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                    <select class="form-select" id="motor_payment" name="metode_pembayaran" required>
                                        <option value="">Pilih Metode Pembayaran</option>
                                        <option value="cash" <?= $sale->payment_method == 'cash' ? 'selected' : '' ?>>Cash</option>
                                        <option value="credit_card" <?= $sale->payment_method == 'credit_card' ? 'selected' : '' ?>>Credit Card</option>
                                        <option value="transfer" <?= $sale->payment_method == 'transfer' ? 'selected' : '' ?>>Transfer Bank</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="motor_notes" class="form-label">Keterangan</label>
                                    <textarea class="form-control" id="motor_notes" name="keterangan" rows="3" 
                                              placeholder="Tambahkan catatan atau keterangan tambahan"><?= $sale->notes ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="card-title mb-0">Detail Harga</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th>Harga Motor:</th>
                                                    <td class="text-end" id="motorPrice">
                                                        Rp <?= number_format($sale->total_amount, 0, ',', '.') ?>
                                                    </td>
                                                </tr>
                                                <tr class="border-top">
                                                    <th class="h5">Total:</th>
                                                    <td class="text-end h5" id="motorTotal">
                                                        Rp <?= number_format($sale->total_amount, 0, ',', '.') ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <a href="<?= base_url('sales') ?>" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Sparepart Sales Tab -->
                <div class="tab-pane fade <?= isset($sale_items[0]) && $sale_items[0]->item_type == 'sparepart' ? 'show active' : '' ?>" 
                     id="sparepart" role="tabpanel">
                    <form action="<?= base_url('sales/update/' . $sale->id) ?>" method="post" id="sparepartForm">
                        <input type="hidden" name="sale_type" value="sparepart">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sparepart_customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="sparepart_customer_id" name="customer_id" required>
                                        <option value="">Pilih Customer</option>
                                        <?php foreach ($customers as $customer): ?>
                                            <option value="<?= $customer->id ?>" <?= $customer->id == $sale->customer_name ? 'selected' : '' ?>>
                                                <?= $customer->name ?> - <?= $customer->phone ?> (<?= $customer->identity_number ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="sparepart_payment" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                    <select class="form-select" id="sparepart_payment" name="metode_pembayaran" required>
                                        <option value="">Pilih Metode Pembayaran</option>
                                        <option value="cash" <?= $sale->payment_method == 'cash' ? 'selected' : '' ?>>Cash</option>
                                        <option value="credit_card" <?= $sale->payment_method == 'credit_card' ? 'selected' : '' ?>>Credit Card</option>
                                        <option value="transfer" <?= $sale->payment_method == 'transfer' ? 'selected' : '' ?>>Transfer Bank</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="sparepart_notes" class="form-label">Keterangan</label>
                                    <textarea class="form-control" id="sparepart_notes" name="keterangan" rows="3"
                                              placeholder="Tambahkan catatan atau keterangan tambahan"><?= $sale->notes ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-primary mb-3">
                                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">Daftar Sparepart</h5>
                                        <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addSparepartModal">
                                            <i class="fas fa-plus me-1"></i>Tambah
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="sparepartTable">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Nama Sparepart</th>
                                                        <th width="100">Jumlah</th>
                                                        <th width="150">Harga</th>
                                                        <th width="150">Subtotal</th>
                                                        <th width="50">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($sale_items as $index => $item): ?>
                                                        <?php if ($item->item_type == 'sparepart'): ?>
                                                            <tr>
                                                                <td>
                                                                    <?= $item->item_name ?>
                                                                    <input type="hidden" name="items[]" value="sparepart_<?= $item->item_id ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control item-qty" 
                                                                           name="quantities[]" value="<?= $item->quantity ?>" 
                                                                           min="1" required>
                                                                </td>
                                                                <td class="text-end">
                                                                    Rp <?= number_format($item->price, 0, ',', '.') ?>
                                                                </td>
                                                                <td class="text-end">
                                                                    Rp <?= number_format($item->subtotal, 0, ',', '.') ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-danger btn-sm btn-delete-item">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                                <tfoot class="table-light">
                                                    <tr>
                                                        <td colspan="3" class="text-end fw-bold">Total:</td>
                                                        <td class="text-end fw-bold" id="sparepartTotal">
                                                            Rp <?= number_format($sale->total_amount, 0, ',', '.') ?>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <a href="<?= base_url('sales') ?>" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Sparepart -->
<div class="modal fade" id="addSparepartModal" tabindex="-1" aria-labelledby="addSparepartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSparepartModalLabel">
                    <i class="fas fa-plus me-2"></i>Tambah Sparepart
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label required" for="sparepartSelect">Sparepart</label>
                    <select class="form-select select2" id="sparepartSelect">
                        <option value="">Pilih Sparepart</option>
                        <?php foreach ($spareparts as $sparepart): ?>
                            <option value="<?= $sparepart->id ?>" 
                                    data-price="<?= $sparepart->harga ?>"
                                    data-name="<?= $sparepart->nama ?>"
                                    data-kategori="<?= $sparepart->kategori ?>"
                                    data-stok="<?= $sparepart->stok ?>">
                                <?= $sparepart->nama ?> - <?= $sparepart->kategori ?> 
                                (Stok: <?= $sparepart->stok ?>) - Rp <?= number_format($sparepart->harga, 0, ',', '.') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="sparepartError" class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label required" for="sparepartQuantity">Jumlah</label>
                    <input type="number" class="form-control" id="sparepartQuantity" min="1" value="1">
                    <div id="quantityError" class="invalid-feedback"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-primary" id="addSparepartBtn">
                    <i class="fas fa-plus me-1"></i>Tambah
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        dropdownParent: $('.modal-content')
    });

    // Handle Motor Form
    $('#motor_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const price = parseFloat(selectedOption.data('price')) || 0;
        const stok = parseInt(selectedOption.data('stok')) || 0;
        
        if (stok === 0) {
            alert('Stok motor tidak tersedia');
            $(this).val('').trigger('change');
            return;
        }
        
        updateMotorPrice(price);
    });

    // Handle Sparepart Form
    $('#addSparepartBtn').on('click', function() {
        const select = $('#sparepartSelect');
        const option = select.find('option:selected');
        const quantity = parseInt($('#sparepartQuantity').val());
        
        if (!select.val()) {
            $('#sparepartError').text('Silakan pilih sparepart').show();
            select.addClass('is-invalid');
            return;
        }
        
        if (!quantity || quantity < 1) {
            $('#quantityError').text('Jumlah harus lebih dari 0').show();
            $('#sparepartQuantity').addClass('is-invalid');
            return;
        }
        
        const stok = parseInt(option.data('stok'));
        if (quantity > stok) {
            $('#quantityError').text('Stok tidak mencukupi').show();
            $('#sparepartQuantity').addClass('is-invalid');
            return;
        }
        
        const id = select.val();
        const name = option.data('name');
        const kategori = option.data('kategori');
        const price = parseFloat(option.data('price'));
        const subtotal = price * quantity;
        
        const row = `
            <tr>
                <td>
                    ${name} <small class="text-muted">(${kategori})</small>
                    <input type="hidden" name="items[]" value="sparepart_${id}">
                </td>
                <td>
                    <input type="number" class="form-control item-qty" name="quantities[]" 
                           value="${quantity}" min="1" required>
                </td>
                <td class="text-end">Rp ${formatRupiah(price)}</td>
                <td class="text-end">Rp ${formatRupiah(subtotal)}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm btn-delete-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        
        $('#sparepartTable tbody').append(row);
        updateSparepartTotal();
        $('#addSparepartModal').modal('hide');
    });

    // Handle Delete Item
    $(document).on('click', '.btn-delete-item', function() {
        if ($('#sparepartTable tbody tr').length > 1) {
            $(this).closest('tr').remove();
            updateSparepartTotal();
        } else {
            alert('Minimal harus ada 1 item');
        }
    });

    // Handle Quantity Change
    $(document).on('change', '.item-qty', function() {
        const row = $(this).closest('tr');
        const price = parseFloat(row.find('td:eq(2)').text().replace(/[^0-9]/g, ''));
        const quantity = parseInt($(this).val()) || 0;
        const subtotal = price * quantity;
        
        row.find('td:eq(3)').text('Rp ' + formatRupiah(subtotal));
        updateSparepartTotal();
    });

    function updateMotorPrice(price) {
        $('#motorPrice').text('Rp ' + formatRupiah(price));
        $('#motorTotal').text('Rp ' + formatRupiah(price));
    }

    function updateSparepartTotal() {
        let total = 0;
        $('#sparepartTable tbody tr').each(function() {
            const subtotalText = $(this).find('td:eq(3)').text().replace('Rp ', '').replace(/\./g, '');
            total += parseInt(subtotalText);
        });
        $('#sparepartTotal').text('Rp ' + formatRupiah(total));
    }

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
});
</script>

<?php $this->load->view('templates/footer'); ?> 
