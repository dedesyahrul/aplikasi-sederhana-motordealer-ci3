<?php $this->load->view('templates/header'); ?>

<!-- Required JavaScript -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Custom CSS -->
<style>
/* Select2 Global Styles */
.select2-container {
    z-index: 1;
}

/* Regular Select2 (outside modal) */
.tab-content .select2-container {
    z-index: 1 !important;
}

/* Select2 in Modal */
.modal-content .select2-container {
    z-index: 1056 !important;
}

.modal-content .select2-dropdown {
    z-index: 1056 !important;
}

/* Modal z-index */
.modal {
    z-index: 1055;
}

.modal-backdrop {
    z-index: 1050;
}

/* Select2 Styling */
.select2-container--bootstrap-5 .select2-selection {
    height: calc(1.5em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    padding: 0;
    line-height: 1.5;
}

.select2-container--bootstrap-5.select2-container--focus .select2-selection {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Table Styles */
.table > :not(caption) > * > * {
    padding: 0.75rem;
    vertical-align: middle;
}

.table-striped > tbody > tr:nth-of-type(odd) > * {
    background-color: rgba(0, 0, 0, 0.02);
}

.table > tbody > tr > td {
    white-space: nowrap;
}

.table > tbody > tr > td:first-child {
    white-space: normal;
}

/* Card Styles */
.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.card-header.bg-primary {
    background-color: #0d6efd !important;
}

/* Form Styles */
.form-label {
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.required-field::after {
    content: " *";
    color: #dc3545;
}

/* Modal Styles */
body.modal-open {
    overflow: auto !important;
    padding-right: 0 !important;
}

.modal {
    padding-right: 0 !important;
}

.modal-backdrop {
    z-index: 1040;
}

.modal-dialog {
    z-index: 1045;
}

/* Select2 in Modal */
.select2-container {
    z-index: 1056 !important;
}

.select2-dropdown {
    z-index: 1056 !important;
}

/* Button Styles */
.btn-light {
    background-color: #ffffff;
    border-color: #dee2e6;
}

.btn-light:hover {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

/* Fix table width */
.table-responsive {
    margin-bottom: 1rem;
}

.table {
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    background-color: #f8f9fa;
}

.table td.text-end,
.table th.text-end {
    padding-right: 1rem;
}

/* Fix currency alignment */
.text-end {
    text-align: right !important;
}

/* Fix total row */
.table tfoot tr {
    border-top: 2px solid #dee2e6;
    font-weight: 700;
}

.table tfoot td {
    background-color: #f8f9fa;
}
</style>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Penjualan Baru</h6>
        </div>
        <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs mb-3" id="salesTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="motor-tab" data-bs-toggle="tab" data-bs-target="#motor" type="button" role="tab">
                        <i class="fas fa-motorcycle me-2"></i>Penjualan Motor
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="sparepart-tab" data-bs-toggle="tab" data-bs-target="#sparepart" type="button" role="tab">
                        <i class="fas fa-cogs me-2"></i>Penjualan Sparepart
                    </button>
                </li>
            </ul>

            <!-- Tab content -->
            <div class="tab-content" id="salesTabContent">
                <!-- Motor Sales Tab -->
                <div class="tab-pane fade show active" id="motor" role="tabpanel">
                    <form action="<?= base_url('sales/store') ?>" method="post" id="motorForm">
                        <input type="hidden" name="sale_type" value="motor">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="motor_customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="motor_customer_id" name="customer_id" required>
                                        <option value="">Pilih Customer</option>
                                        <?php foreach ($customers as $customer): ?>
                                            <option value="<?= $customer->id ?>">
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
                                            <option value="motor_<?= $motor->id ?>" 
                                                    data-price="<?= $motor->harga ?>"
                                                    data-stok="<?= $motor->stok ?>">
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
                                        <option value="cash">Cash</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="transfer">Transfer Bank</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="motor_notes" class="form-label">Keterangan</label>
                                    <textarea class="form-control" id="motor_notes" name="keterangan" rows="3" 
                                              placeholder="Tambahkan catatan atau keterangan tambahan"></textarea>
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
                                                    <td class="text-end" id="motorPrice">Rp 0</td>
                                                </tr>
                                                <tr class="border-top">
                                                    <th class="h5">Total:</th>
                                                    <td class="text-end h5" id="motorTotal">Rp 0</td>
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
                                <i class="fas fa-save me-1"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Sparepart Sales Tab -->
                <div class="tab-pane fade" id="sparepart" role="tabpanel">
                    <form action="<?= base_url('sales/store') ?>" method="post" id="sparepartForm">
                        <input type="hidden" name="sale_type" value="sparepart">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sparepart_customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="sparepart_customer_id" name="customer_id" required>
                                        <option value="">Pilih Customer</option>
                                        <?php foreach ($customers as $customer): ?>
                                            <option value="<?= $customer->id ?>">
                                                <?= $customer->name ?> - <?= $customer->phone ?> (<?= $customer->identity_number ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="sparepart_payment" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                    <select class="form-select" id="sparepart_payment" name="metode_pembayaran" required>
                                        <option value="">Pilih Metode Pembayaran</option>
                                        <option value="cash">Cash</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="transfer">Transfer Bank</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="sparepart_notes" class="form-label">Keterangan</label>
                                    <textarea class="form-control" id="sparepart_notes" name="keterangan" rows="3"
                                              placeholder="Tambahkan catatan atau keterangan tambahan"></textarea>
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
                                                </tbody>
                                                <tfoot class="table-light">
                                                    <tr>
                                                        <td colspan="3" class="text-end fw-bold">Total:</td>
                                                        <td class="text-end fw-bold" id="sparepartTotal">Rp 0</td>
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
                                <i class="fas fa-save me-1"></i>Simpan
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
    // Initialize regular Select2 dropdowns
    initializeRegularSelect2();
    
    // Initialize modal
    const sparepartModal = new bootstrap.Modal(document.getElementById('addSparepartModal'));
    
    // Handle modal events
    $('#addSparepartModal').on('show.bs.modal', function () {
        // Reset any previous modal state
        resetModalState();
        initializeModalSelect2();
        resetSparepartForm();
    });

    $('#addSparepartModal').on('hidden.bs.modal', function () {
        // Reset modal state
        resetModalState();
        resetSparepartForm();
    });

    // Handle Add Sparepart Button
    $('#addSparepartBtn').on('click', function() {
        if (validateSparepartForm()) {
            if (addSparepart()) {
                $('#addSparepartModal').modal('hide');
            }
        }
    });

    // Handle Motor Form
    handleMotorForm();
    
    // Handle Sparepart Form
    handleSparepartForm();
});

function resetModalState() {
    // Remove modal-related attributes and classes
    $('body')
        .removeAttr('style')
        .removeAttr('data-bs-padding-right')
        .removeAttr('data-bs-overflow')
        .removeClass('modal-open');
    
    // Remove any stray backdrops
    $('.modal-backdrop').remove();
}

function initializeRegularSelect2() {
    $('.select2:not(#sparepartSelect)').each(function() {
        if ($(this).data('select2')) {
            $(this).select2('destroy');
        }
        $(this).select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Pilih opsi...',
            allowClear: true,
            dropdownParent: $(this).closest('.tab-pane')
        });
    });
}

function initializeModalSelect2() {
    const $select = $('#sparepartSelect');
    
    if ($select.data('select2')) {
        $select.select2('destroy');
    }
    
    $select.select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Pilih sparepart...',
        allowClear: true,
        dropdownParent: $('#addSparepartModal .modal-content'),
        closeOnSelect: true
    }).on('select2:select', function() {
        setTimeout(function() {
            $('#sparepartQuantity').focus();
        }, 100);
    });
}

function resetSparepartForm() {
    const $select = $('#sparepartSelect');
    if ($select.data('select2')) {
        $select.val(null).trigger('change');
    }
    $('#sparepartQuantity').val(1);
    resetValidation();
}

function handleMotorForm() {
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

    $('#motorForm').on('submit', function(e) {
        if (!$('#motor_id').val()) {
            e.preventDefault();
            alert('Silakan pilih motor yang akan dijual');
            return false;
        }
        return true;
    });
}

function handleSparepartForm() {
    $(document).on('click', '.btn-delete-sparepart', function() {
        $(this).closest('tr').remove();
        updateSparepartTotal();
    });

    $('#sparepartForm').on('submit', function(e) {
        if ($('#sparepartTable tbody tr').length === 0) {
            e.preventDefault();
            alert('Silakan tambahkan minimal satu sparepart');
            return false;
        }
        return true;
    });
}

function validateSparepartForm() {
    const select = $('#sparepartSelect');
    const quantityInput = $('#sparepartQuantity');
    let isValid = true;

    // Reset validation
    resetValidation();

    // Validate sparepart selection
    if (!select.val()) {
        showError(select, 'sparepartError', 'Silakan pilih sparepart');
        isValid = false;
    }

    // Validate quantity
    const quantity = parseInt(quantityInput.val());
    const option = select.find('option:selected');
    const stok = parseInt(option.data('stok'));

    if (!quantity || quantity < 1) {
        showError(quantityInput, 'quantityError', 'Jumlah harus lebih dari 0');
        isValid = false;
    } else if (quantity > stok) {
        showError(quantityInput, 'quantityError', 'Stok tidak mencukupi');
        isValid = false;
    }

    return isValid;
}

function addSparepart() {
    const select = $('#sparepartSelect');
    const option = select.find('option:selected');
    const quantity = parseInt($('#sparepartQuantity').val());
    
    const id = select.val();
    const name = option.data('name');
    const kategori = option.data('kategori');
    const price = parseFloat(option.data('price'));
    const subtotal = price * quantity;

    // Check existing sparepart
    const existingRow = findExistingSparepart(id);
    
    if (existingRow) {
        updateExistingSparepart(existingRow, quantity, price, option.data('stok'));
    } else {
        addNewSparepart(id, name, kategori, quantity, price, subtotal);
    }

    updateSparepartTotal();
}

function findExistingSparepart(id) {
    let existingRow = null;
    $('#sparepartTable tbody tr').each(function() {
        const existingId = $(this).find('input[name="items[]"]').val().split('_')[1];
        if (existingId === id) {
            existingRow = $(this);
            return false;
        }
    });
    return existingRow;
}

function updateExistingSparepart(row, newQuantity, price, maxStok) {
    const existingQuantity = parseInt(row.find('input[name="quantities[]"]').val());
    const updatedQuantity = existingQuantity + newQuantity;
    
    if (updatedQuantity > maxStok) {
        showError($('#sparepartQuantity'), 'quantityError', 'Total jumlah melebihi stok tersedia');
        return false;
    }

    const newSubtotal = price * updatedQuantity;
    row.find('td:eq(1)').text(updatedQuantity);
    row.find('td:eq(2)').text('Rp ' + numberFormat(price));
    row.find('td:eq(3)').text('Rp ' + numberFormat(newSubtotal));
    row.find('input[name="quantities[]"]').val(updatedQuantity);
    return true;
}

function addNewSparepart(id, name, kategori, quantity, price, subtotal) {
    const row = `
        <tr>
            <td>${name} <small class="text-muted">(${kategori})</small></td>
            <td class="text-center">${quantity}</td>
            <td class="text-end">Rp ${numberFormat(price)}</td>
            <td class="text-end">Rp ${numberFormat(subtotal)}</td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm btn-delete-sparepart">
                    <i class="fas fa-trash"></i>
                </button>
                <input type="hidden" name="items[]" value="sparepart_${id}">
                <input type="hidden" name="quantities[]" value="${quantity}">
            </td>
        </tr>
    `;
    $('#sparepartTable tbody').append(row);
}

function updateSparepartTotal() {
    let total = 0;
    $('#sparepartTable tbody tr').each(function() {
        const subtotalText = $(this).find('td:eq(3)').text().replace('Rp ', '').replace(/\./g, '');
        total += parseInt(subtotalText);
    });
    $('#sparepartTotal').text('Rp ' + numberFormat(total));
}

function updateMotorPrice(price) {
    $('#motorPrice').text('Rp ' + numberFormat(price));
    $('#motorTotal').text('Rp ' + numberFormat(price));
}

function formatCurrency(amount) {
    return 'Rp ' + numberFormat(amount);
}

function numberFormat(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function resetValidation() {
    $('#sparepartError, #quantityError').hide();
    $('#sparepartSelect, #sparepartQuantity').removeClass('is-invalid');
    $('.invalid-feedback').empty();
}

function showError(element, errorId, message) {
    element.addClass('is-invalid');
    $(`#${errorId}`).text(message).show();
}
</script>

<?php $this->load->view('templates/footer'); ?> 
