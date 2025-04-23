<?php $this->load->view('templates/header'); ?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Penjualan</h1>
        <a href="<?= base_url('sales') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
    <div class="card-body">
            <form action="<?= base_url('sales/update/' . $sale->id) ?>" method="post" id="editForm">
            <div class="row">
                <div class="col-md-6">
                        <div class="card border-info mb-3">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-user me-2"></i>Informasi Customer
                                </h5>
                            </div>
                            <div class="card-body">
                    <div class="mb-3">
                                    <label for="customer_id" class="form-label required">Customer</label>
                                    <select class="form-select select2 <?= form_error('customer_id') ? 'is-invalid' : '' ?>" 
                                            id="customer_id" name="customer_id" required>
                            <option value="">Pilih Customer</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer->id ?>" <?= set_select('customer_id', $customer->id, ($customer->id == $sale->customer_id)) ?>>
                                    <?= $customer->name ?> - <?= $customer->phone ?>
                                                <?php if($customer->identity_number): ?>
                                                    (<?= $customer->identity_number ?>)
                                                <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('customer_id', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <div class="mb-3">
                                    <label for="tanggal_jual" class="form-label required">Tanggal Penjualan</label>
                        <input type="date" class="form-control <?= form_error('tanggal_jual') ? 'is-invalid' : '' ?>" 
                                        id="tanggal_jual" name="tanggal_jual" 
                                        value="<?= set_value('tanggal_jual', date('Y-m-d', strtotime($sale->created_at))) ?>"
                                        required>
                        <?= form_error('tanggal_jual', '<div class="invalid-feedback">', '</div>') ?>
                    </div>
                </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-primary mb-3">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-money-bill me-2"></i>Detail Pembayaran
                                </h5>
                            </div>
                            <div class="card-body">
                    <div class="mb-3">
                                    <label for="metode_pembayaran" class="form-label required">Metode Pembayaran</label>
                        <select class="form-select <?= form_error('metode_pembayaran') ? 'is-invalid' : '' ?>" 
                                            id="metode_pembayaran" name="metode_pembayaran" required>
                            <option value="">Pilih Metode Pembayaran</option>
                                        <option value="cash" <?= set_select('metode_pembayaran', 'cash', ($sale->payment_method == 'cash')) ?>>
                                            <i class="fas fa-money-bill-wave"></i> Tunai
                                        </option>
                                        <option value="credit_card" <?= set_select('metode_pembayaran', 'credit_card', ($sale->payment_method == 'credit_card')) ?>>
                                            <i class="fas fa-credit-card"></i> Kartu Kredit
                                        </option>
                                        <option value="transfer" <?= set_select('metode_pembayaran', 'transfer', ($sale->payment_method == 'transfer')) ?>>
                                            <i class="fas fa-university"></i> Transfer Bank
                                        </option>
                        </select>
                        <?= form_error('metode_pembayaran', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <div class="mb-3">
                                    <label for="status" class="form-label required">Status Pembayaran</label>
                                    <select class="form-select <?= form_error('status') ? 'is-invalid' : '' ?>" 
                                            id="status" name="status" required>
                                        <option value="pending" <?= set_select('status', 'pending', ($sale->status == 'pending')) ?>>Menunggu Pembayaran</option>
                                        <option value="completed" <?= set_select('status', 'completed', ($sale->status == 'completed')) ?>>Selesai</option>
                                        <option value="cancelled" <?= set_select('status', 'cancelled', ($sale->status == 'cancelled')) ?>>Dibatalkan</option>
                                    </select>
                                    <?= form_error('status', '<div class="invalid-feedback">', '</div>') ?>
                                </div>

                                <div class="mb-0">
                        <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                                              placeholder="Tambahkan catatan atau keterangan tambahan"><?= set_value('keterangan', $sale->notes) ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-success mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-shopping-cart me-2"></i>Item Penjualan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th width="200">Tipe</th>
                                        <th>Item</th>
                                        <th width="100">Jumlah</th>
                                        <th width="200">Harga</th>
                                        <th width="200">Subtotal</th>
                                        <th width="50">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sale_items as $index => $item): ?>
                                    <tr class="item-row">
                                        <td>
                                            <select class="form-select item-type" name="items[<?= $index ?>][type]" required>
                                                <option value="motor" <?= $item->item_type == 'motor' ? 'selected' : '' ?>>Motor</option>
                                                <option value="sparepart" <?= $item->item_type == 'sparepart' ? 'selected' : '' ?>>Sparepart</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select select2 item-select" name="items[<?= $index ?>][id]" required>
                                                <option value="">Pilih Item</option>
                                            </select>
                                            <input type="hidden" name="items[<?= $index ?>][price]" class="item-price" value="<?= $item->price ?>">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control item-qty" name="items[<?= $index ?>][qty]" 
                                                   value="<?= $item->quantity ?>" min="1" required>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" class="form-control text-end item-price-display" 
                                                       value="<?= number_format($item->price, 0, ',', '.') ?>" readonly>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" class="form-control text-end item-subtotal" 
                                                       value="<?= number_format($item->subtotal, 0, ',', '.') ?>" readonly>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm btn-delete-item">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6">
                                            <button type="button" class="btn btn-success btn-sm" id="addItem">
                                                <i class="fas fa-plus me-1"></i>Tambah Item
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Total:</td>
                                        <td colspan="2">
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" class="form-control text-end" id="totalAmount" 
                                                       value="<?= number_format($sale->total_amount, 0, ',', '.') ?>" readonly>
                                                <input type="hidden" name="total_amount" id="totalAmountHidden" 
                                                       value="<?= $sale->total_amount ?>">
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                    </div>
                </div>
            </div>

                <div class="d-flex justify-content-end">
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

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Select2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize select2
    initializeSelect2();

    // Load initial items
    $('.item-type').each(function() {
        loadItems($(this));
    });

    // Handle item type change
    $(document).on('change', '.item-type', function() {
        loadItems($(this));
    });

    // Handle item selection
    $(document).on('change', '.item-select', function() {
        var row = $(this).closest('tr');
        var selectedOption = $(this).find('option:selected');
        var price = selectedOption.data('price') || 0;
        
        row.find('.item-price').val(price);
        row.find('.item-price-display').val(formatRupiah(price));
        calculateSubtotal(row);
    });

    // Handle quantity change
    $(document).on('change', '.item-qty', function() {
        calculateSubtotal($(this).closest('tr'));
    });

    // Add new item row
    $('#addItem').click(function() {
        var index = $('.item-row').length;
        var newRow = `
            <tr class="item-row">
                <td>
                    <select class="form-select item-type" name="items[${index}][type]" required>
                        <option value="motor">Motor</option>
                        <option value="sparepart">Sparepart</option>
                    </select>
                </td>
                <td>
                    <select class="form-select select2 item-select" name="items[${index}][id]" required>
                        <option value="">Pilih Item</option>
                    </select>
                    <input type="hidden" name="items[${index}][price]" class="item-price" value="0">
                </td>
                <td>
                    <input type="number" class="form-control item-qty" name="items[${index}][qty]" value="1" min="1" required>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control text-end item-price-display" value="0" readonly>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control text-end item-subtotal" value="0" readonly>
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm btn-delete-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#itemsTable tbody').append(newRow);
        initializeSelect2();
        loadItems($('.item-row:last .item-type'));
    });

    // Delete item row
    $(document).on('click', '.btn-delete-item', function() {
        if ($('.item-row').length > 1) {
            $(this).closest('tr').remove();
            calculateTotal();
        } else {
            alert('Minimal harus ada 1 item');
        }
    });

    // Form validation
    $('#editForm').on('submit', function(e) {
        var isValid = true;
        
        // Reset validation
        $('.is-invalid').removeClass('is-invalid');
        
        // Validate required fields
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Silakan lengkapi semua field yang wajib diisi');
        }
        
        return isValid;
    });

    function initializeSelect2() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Pilih opsi...',
            allowClear: true
        });
    }

    function loadItems(typeSelect) {
        var row = typeSelect.closest('tr');
        var itemSelect = row.find('.item-select');
        var selectedType = typeSelect.val();
        var currentItemId = itemSelect.val();

        $.get('<?= base_url('sales/get_items/') ?>' + selectedType, function(data) {
            var items = JSON.parse(data);
            var options = '<option value="">Pilih Item</option>';
            
            items.forEach(function(item) {
                var price = selectedType === 'motor' ? item.harga : item.harga_jual;
                var text = selectedType === 'motor' 
                    ? `${item.merk} ${item.model} ${item.tahun} - ${item.warna} (Stok: ${item.stok})`
                    : `${item.nama} (Stok: ${item.stok})`;
                
                options += `<option value="${item.id}" data-price="${price}" ${item.id == currentItemId ? 'selected' : ''}>
                    ${text} - Rp ${formatRupiah(price)}
                </option>`;
            });
            
            itemSelect.html(options).trigger('change');
        });
    }

    function calculateSubtotal(row) {
        var price = parseFloat(row.find('.item-price').val()) || 0;
        var qty = parseInt(row.find('.item-qty').val()) || 0;
        var subtotal = price * qty;
        
        row.find('.item-subtotal').val(formatRupiah(subtotal));
        calculateTotal();
    }

    function calculateTotal() {
        var total = 0;
        $('.item-subtotal').each(function() {
            total += parseFloat($(this).val().replace(/[^0-9]/g, '')) || 0;
        });
        
        $('#totalAmount').val(formatRupiah(total));
        $('#totalAmountHidden').val(total);
    }

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
});
</script>

<style>
/* Form Styles */
.form-label {
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.form-label.required::after {
    content: " *";
    color: #dc3545;
}

.select2-container--bootstrap-5 .select2-selection {
    min-height: 38px;
    padding: 0.375rem 0.75rem;
}

.input-group > .form-control.is-invalid,
.input-group > .form-select.is-invalid {
    z-index: 0;
}

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

/* Button Styles */
.btn {
    font-weight: 500;
}

.btn-group > .btn {
    padding: 0.25rem 0.5rem;
}

/* Table Styles */
.table-bordered > :not(caption) > * > * {
    border-width: 1px;
}

.table input[readonly] {
    background-color: #f8f9fa;
}
</style>

<?php $this->load->view('templates/footer'); ?> 
