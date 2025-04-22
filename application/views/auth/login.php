<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Dealer Motor</title>
    
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    
    <!-- Custom fonts -->
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    
    <!-- Custom styles -->
    <style>
        body {
            background-color: #f8f9fc;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 400px;
            width: 100%;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        .login-card .card-header {
            background: none;
            border: none;
            padding-bottom: 2rem;
            text-align: center;
        }
        .login-card .card-header h4 {
            color: #5a5c69;
            font-weight: 700;
            margin: 0;
        }
        .btn-login {
            font-size: 0.9rem;
            letter-spacing: 0.05rem;
            padding: 0.75rem 1rem;
        }
        .alert {
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .invalid-feedback {
            display: block;
            margin-top: 0.25rem;
        }
        .input-group-text {
            background-color: #f8f9fc;
            border-right: none;
        }
        .form-control {
            border-left: none;
        }
        .form-control:focus {
            border-color: #d1d3e2;
            box-shadow: none;
        }
        .input-group-append .input-group-text {
            border-left: none;
            border-right: 1px solid #d1d3e2;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card login-card">
            <div class="card-header">
                <h4>SISTEM DEALER MOTOR</h4>
                <small class="text-muted">Silakan masuk untuk melanjutkan</small>
            </div>
            <div class="card-body">
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('auth/login') ?>" method="POST" class="needs-validation" novalidate>
                    <div class="form-group">
                        <label for="username">Nama Pengguna</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                            <input type="text" 
                                   class="form-control <?= form_error('username') ? 'is-invalid' : '' ?>" 
                                   id="username" 
                                   name="username" 
                                   value="<?= set_value('username') ?>"
                                   placeholder="Masukkan nama pengguna"
                                   required
                                   autocomplete="username">
                        </div>
                        <?php if (form_error('username')): ?>
                            <div class="invalid-feedback">
                                <?= form_error('username') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="password">Kata Sandi</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                            <input type="password" 
                                   class="form-control <?= form_error('password') ? 'is-invalid' : '' ?>" 
                                   id="password" 
                                   name="password"
                                   placeholder="Masukkan kata sandi"
                                   required
                                   autocomplete="current-password">
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password" style="cursor: pointer;" title="Tampilkan/Sembunyikan kata sandi">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <?php if (form_error('password')): ?>
                            <div class="invalid-feedback">
                                <?= form_error('password') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-login">
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <script>
    $(document).ready(function() {
        // Toggle password visibility
        $('.toggle-password').click(function() {
            const input = $(this).closest('.input-group').find('input');
            const icon = $(this).find('i');
            
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
                $(this).attr('title', 'Sembunyikan kata sandi');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
                $(this).attr('title', 'Tampilkan kata sandi');
            }
        });

        // Auto close alerts after 5 seconds
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 5000);

        // Client-side form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
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
    });
    </script>
</body>
</html> 
