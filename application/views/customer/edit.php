<?php $this->load->view('templates/header'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Data Pelanggan</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('customer/update/'.$customer->id) ?>" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control <?= form_error('name') ? 'is-invalid' : '' ?>" 
                            id="name" name="name" value="<?= set_value('name', $customer->name) ?>">
                        <?= form_error('name', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control <?= form_error('phone') ? 'is-invalid' : '' ?>" 
                            id="phone" name="phone" value="<?= set_value('phone', $customer->phone) ?>">
                        <?= form_error('phone', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control <?= form_error('email') ? 'is-invalid' : '' ?>" 
                            id="email" name="email" value="<?= set_value('email', $customer->email) ?>">
                        <?= form_error('email', '<div class="invalid-feedback">', '</div>') ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="identity_type" class="form-label">Jenis Identitas</label>
                        <select class="form-select <?= form_error('identity_type') ? 'is-invalid' : '' ?>" 
                            id="identity_type" name="identity_type">
                            <option value="ktp" <?= set_select('identity_type', 'ktp', ($customer->identity_type == 'ktp')) ?>>KTP</option>
                            <option value="sim" <?= set_select('identity_type', 'sim', ($customer->identity_type == 'sim')) ?>>SIM</option>
                            <option value="passport" <?= set_select('identity_type', 'passport', ($customer->identity_type == 'passport')) ?>>Passport</option>
                        </select>
                        <?= form_error('identity_type', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <div class="mb-3">
                        <label for="identity_number" class="form-label">Nomor Identitas</label>
                        <input type="text" class="form-control <?= form_error('identity_number') ? 'is-invalid' : '' ?>" 
                            id="identity_number" name="identity_number" value="<?= set_value('identity_number', $customer->identity_number) ?>">
                        <?= form_error('identity_number', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control <?= form_error('address') ? 'is-invalid' : '' ?>" 
                            id="address" name="address" rows="4"><?= set_value('address', $customer->address) ?></textarea>
                        <?= form_error('address', '<div class="invalid-feedback">', '</div>') ?>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <a href="<?= base_url('customer') ?>" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?> 
