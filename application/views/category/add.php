<?php $this->load->view('templates/header'); ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Kategori</h1>
        <a href="<?= base_url('category') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if (validation_errors()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= validation_errors() ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">
            <form action="<?= base_url('category/store') ?>" method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= set_value('name') ?>" required>
                    <div class="invalid-feedback">
                        Nama kategori harus diisi
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?= set_value('description') ?></textarea>
                    <div class="invalid-feedback">
                        Deskripsi harus diisi
                    </div>
                </div>

                <hr>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
})()
</script>

<?php $this->load->view('templates/footer'); ?> 
