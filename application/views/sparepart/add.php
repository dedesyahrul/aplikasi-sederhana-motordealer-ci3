<?php $this->load->view('templates/header'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Tambah Sparepart Baru</h2>
    <a href="<?= base_url('sparepart') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<?php if (validation_errors()): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= validation_errors() ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('sparepart/store') ?>" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode Sparepart</label>
                        <input type="text" class="form-control" id="kode" name="kode" value="<?= set_value('kode') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Sparepart</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= set_value('nama') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Mesin" <?= set_select('kategori', 'Mesin') ?>>Mesin</option>
                            <option value="Body" <?= set_select('kategori', 'Body') ?>>Body</option>
                            <option value="Kelistrikan" <?= set_select('kategori', 'Kelistrikan') ?>>Kelistrikan</option>
                            <option value="Aksesoris" <?= set_select('kategori', 'Aksesoris') ?>>Aksesoris</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="harga" name="harga" value="<?= set_value('harga') ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok" value="<?= set_value('stok', '0') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?= set_value('deskripsi') ?></textarea>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan
            </button>
        </form>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?> 
