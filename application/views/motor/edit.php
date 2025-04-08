<?php $this->load->view('templates/header'); ?>

<div class="card">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Edit Data Motor</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('motor/update/' . $motor->id) ?>" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="merk" class="form-label">Merk Motor</label>
                        <input type="text" class="form-control <?= form_error('merk') ? 'is-invalid' : '' ?>" 
                            id="merk" name="merk" value="<?= set_value('merk', $motor->merk) ?>">
                        <?= form_error('merk', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <div class="mb-3">
                        <label for="model" class="form-label">Model</label>
                        <input type="text" class="form-control <?= form_error('model') ? 'is-invalid' : '' ?>" 
                            id="model" name="model" value="<?= set_value('model', $motor->model) ?>">
                        <?= form_error('model', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <div class="mb-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <input type="number" class="form-control <?= form_error('tahun') ? 'is-invalid' : '' ?>" 
                            id="tahun" name="tahun" value="<?= set_value('tahun', $motor->tahun) ?>">
                        <?= form_error('tahun', '<div class="invalid-feedback">', '</div>') ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="warna" class="form-label">Warna</label>
                        <input type="text" class="form-control <?= form_error('warna') ? 'is-invalid' : '' ?>" 
                            id="warna" name="warna" value="<?= set_value('warna', $motor->warna) ?>">
                        <?= form_error('warna', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" class="form-control <?= form_error('harga') ? 'is-invalid' : '' ?>" 
                            id="harga" name="harga" value="<?= set_value('harga', $motor->harga) ?>">
                        <?= form_error('harga', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control <?= form_error('stok') ? 'is-invalid' : '' ?>" 
                            id="stok" name="stok" value="<?= set_value('stok', $motor->stok) ?>">
                        <?= form_error('stok', '<div class="invalid-feedback">', '</div>') ?>
                    </div>
                </div>

                <div class="col-12">
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control <?= form_error('deskripsi') ? 'is-invalid' : '' ?>" 
                            id="deskripsi" name="deskripsi" rows="3"><?= set_value('deskripsi', $motor->deskripsi) ?></textarea>
                        <?= form_error('deskripsi', '<div class="invalid-feedback">', '</div>') ?>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <a href="<?= base_url('motor') ?>" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
