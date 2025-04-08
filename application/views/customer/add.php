<?php $this->load->view('templates/header'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    <a href="<?= base_url('customer') ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="<?= base_url('customer/store') ?>" method="post" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label required">Nama Lengkap</label>
                                <input type="text" class="form-control <?= form_error('name') ? 'is-invalid' : '' ?>" 
                                    id="name" name="name" value="<?= set_value('name') ?>" required>
                                <div class="invalid-feedback">
                                    <?= form_error('name') ?: 'Nama lengkap harus diisi' ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label required">Nomor Telepon</label>
                                <input type="tel" class="form-control <?= form_error('phone') ? 'is-invalid' : '' ?>" 
                                    id="phone" name="phone" value="<?= set_value('phone') ?>" required>
                                <div class="invalid-feedback">
                                    <?= form_error('phone') ?: 'Nomor telepon harus diisi' ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control <?= form_error('email') ? 'is-invalid' : '' ?>" 
                                    id="email" name="email" value="<?= set_value('email') ?>">
                                <div class="invalid-feedback">
                                    <?= form_error('email') ?: 'Format email tidak valid' ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="identity_type" class="form-label required">Jenis Identitas</label>
                                <select class="form-select <?= form_error('identity_type') ? 'is-invalid' : '' ?>" 
                                    id="identity_type" name="identity_type" required>
                                    <option value="">Pilih Jenis Identitas</option>
                                    <option value="KTP" <?= set_select('identity_type', 'KTP') ?>>KTP</option>
                                    <option value="SIM" <?= set_select('identity_type', 'SIM') ?>>SIM</option>
                                    <option value="Passport" <?= set_select('identity_type', 'Passport') ?>>Passport</option>
                                </select>
                                <div class="invalid-feedback">
                                    <?= form_error('identity_type') ?: 'Jenis identitas harus dipilih' ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="identity_number" class="form-label required">Nomor Identitas</label>
                                <input type="text" class="form-control <?= form_error('identity_number') ? 'is-invalid' : '' ?>" 
                                    id="identity_number" name="identity_number" value="<?= set_value('identity_number') ?>" required>
                                <div class="invalid-feedback">
                                    <?= form_error('identity_number') ?: 'Nomor identitas harus diisi' ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label required">Alamat</label>
                                <textarea class="form-control <?= form_error('address') ? 'is-invalid' : '' ?>" 
                                    id="address" name="address" rows="4" required><?= set_value('address') ?></textarea>
                                <div class="invalid-feedback">
                                    <?= form_error('address') ?: 'Alamat harus diisi' ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('customer') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Informasi</h6>
            </div>
            <div class="card-body">
                <div class="small text-muted">
                    <ul class="mb-0">
                        <li>Field dengan tanda <span class="text-danger">*</span> wajib diisi</li>
                        <li>Nomor telepon harus valid dan aktif</li>
                        <li>Email (opsional) harus menggunakan format yang valid</li>
                        <li>Nomor identitas harus sesuai dengan jenis identitas yang dipilih</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Phone number validation
    $('#phone').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 13) {
            value = value.substr(0, 13);
        }
        $(this).val(value);
    });

    // Identity number validation based on type
    $('#identity_type').change(function() {
        const type = $(this).val();
        const input = $('#identity_number');
        
        input.val('');
        
        switch(type) {
            case 'KTP':
                input.attr('maxlength', 16);
                break;
            case 'SIM':
                input.attr('maxlength', 12);
                break;
            case 'Passport':
                input.attr('maxlength', 9);
                break;
            default:
                input.removeAttr('maxlength');
        }
    });

    $('#identity_number').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        const type = $('#identity_type').val();
        
        if (type === 'Passport') {
            value = value.toUpperCase();
        }
        
        const maxLength = $(this).attr('maxlength');
        if (maxLength && value.length > maxLength) {
            value = value.substr(0, maxLength);
        }
        
        $(this).val(value);
    });
});
</script>

<?php $this->load->view('templates/footer'); ?>


