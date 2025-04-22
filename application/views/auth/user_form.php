<?php $this->load->view('templates/header'); ?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= isset($user) ? 'Edit User' : 'Tambah User' ?></h1>
        <a href="<?= base_url('auth/users') ?>" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="<?= isset($user) ? base_url('auth/edit_user/'.$user->id) : base_url('auth/add_user') ?>" 
                          method="POST" 
                          class="needs-validation" 
                          novalidate>
                        
                        <div class="form-group row">
                            <label for="username" class="col-sm-2 col-form-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" 
                                       class="form-control <?= form_error('username') ? 'is-invalid' : '' ?>" 
                                       id="username" 
                                       name="username"
                                       value="<?= set_value('username', isset($user) ? $user->username : '') ?>"
                                       required>
                                <?= form_error('username', '<div class="invalid-feedback">', '</div>') ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-10">
                                <input type="text" 
                                       class="form-control <?= form_error('name') ? 'is-invalid' : '' ?>" 
                                       id="name" 
                                       name="name"
                                       value="<?= set_value('name', isset($user) ? $user->name : '') ?>"
                                       required>
                                <?= form_error('name', '<div class="invalid-feedback">', '</div>') ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" 
                                       class="form-control <?= form_error('email') ? 'is-invalid' : '' ?>" 
                                       id="email" 
                                       name="email"
                                       value="<?= set_value('email', isset($user) ? $user->email : '') ?>"
                                       required>
                                <?= form_error('email', '<div class="invalid-feedback">', '</div>') ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="role_id" class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-10">
                                <select class="form-control <?= form_error('role_id') ? 'is-invalid' : '' ?>" 
                                        id="role_id" 
                                        name="role_id"
                                        required>
                                    <option value="">Pilih Role</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role->id ?>" 
                                            <?= set_select('role_id', $role->id, 
                                                isset($user) && $user->role_id == $role->id) ?>>
                                            <?= ucfirst($role->name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('role_id', '<div class="invalid-feedback">', '</div>') ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" 
                                       class="form-control <?= form_error('password') ? 'is-invalid' : '' ?>" 
                                       id="password" 
                                       name="password"
                                       <?= isset($user) ? '' : 'required' ?>>
                                <?php if (isset($user)): ?>
                                    <small class="form-text text-muted">
                                        Kosongkan jika tidak ingin mengubah password
                                    </small>
                                <?php endif; ?>
                                <?= form_error('password', '<div class="invalid-feedback">', '</div>') ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="confirm_password" class="col-sm-2 col-form-label">Konfirmasi Password</label>
                            <div class="col-sm-10">
                                <input type="password" 
                                       class="form-control <?= form_error('confirm_password') ? 'is-invalid' : '' ?>" 
                                       id="confirm_password" 
                                       name="confirm_password"
                                       <?= isset($user) ? '' : 'required' ?>>
                                <?= form_error('confirm_password', '<div class="invalid-feedback">', '</div>') ?>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>Simpan
                                </button>
                                <a href="<?= base_url('auth/users') ?>" class="btn btn-secondary">
                                    Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
    'use strict';
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>

<?php $this->load->view('templates/footer'); ?> 
