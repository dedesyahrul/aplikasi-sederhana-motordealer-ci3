<?php $this->load->view('templates/header'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Kategori</h1>
    <a href="<?= base_url('category') ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form action="<?= base_url('category/update/' . $category->id) ?>" method="post" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="name" class="form-label required">Nama Kategori</label>
                        <input type="text" class="form-control <?= form_error('name') ? 'is-invalid' : '' ?>" 
                            id="name" name="name" value="<?= set_value('name', $category->name) ?>" required>
                        <div class="invalid-feedback">
                            <?= form_error('name') ?: 'Nama kategori harus diisi' ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label required">Deskripsi</label>
                        <textarea class="form-control <?= form_error('description') ? 'is-invalid' : '' ?>" 
                            id="description" name="description" rows="4" required><?= set_value('description', $category->description) ?></textarea>
                        <div class="invalid-feedback">
                            <?= form_error('description') ?: 'Deskripsi harus diisi' ?>
                        </div>
                    </div>

                    <hr>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="<?= base_url('category') ?>" class="btn btn-secondary">
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
                        <li>Nama kategori harus unik</li>
                        <li>Deskripsi sebaiknya menjelaskan kategori dengan jelas</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?> 
