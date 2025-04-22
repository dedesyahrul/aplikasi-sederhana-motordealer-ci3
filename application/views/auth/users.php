<?php $this->load->view('templates/header'); ?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen User</h1>
        <a href="<?= base_url('auth/add_user') ?>" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus fa-sm"></i> Tambah User
        </a>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-1"></i>
        <?= $this->session->flashdata('success'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-1"></i>
        <?= $this->session->flashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Login Terakhir</th>
                            <th class="text-center" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($users) && !empty($users)): ?>
                            <?php $no = 1; foreach ($users as $user): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $user->username ?></td>
                                <td><?= $user->name ?></td>
                                <td><?= $user->email ?></td>
                                <td>
                                    <span class="badge badge-info">
                                        <?= ucfirst($user->role_name) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $user->is_active ? 'success' : 'danger' ?>">
                                        <?= $user->is_active ? 'Aktif' : 'Non-aktif' ?>
                                    </span>
                                </td>
                                <td><?= $user->last_login ? date('d/m/Y H:i', strtotime($user->last_login)) : '-' ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="<?= base_url('auth/edit_user/'.$user->id) ?>" 
                                           class="btn btn-warning btn-sm"
                                           data-toggle="tooltip" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($user->id !== $this->session->userdata('user_id')): ?>
                                        <a href="javascript:void(0)" 
                                           class="btn btn-danger btn-sm btn-delete"
                                           data-id="<?= $user->id ?>"
                                           data-toggle="tooltip" 
                                           title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data user</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus user ini?</p>
                <p class="text-warning"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#dataTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        },
        "responsive": true
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Handle delete confirmation
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        var userId = $(this).data('id');
        $('#deleteForm').attr('action', '<?= base_url('auth/delete_user/') ?>' + userId);
        $('#deleteModal').modal('show');
    });

    // Auto close alerts
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 3000);
});
</script>

<?php $this->load->view('templates/footer'); ?> 
