<?php $this->load->view('templates/header'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Servis</h5>
            <a href="<?= base_url('service/add') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Servis
            </a>
        </div>
        <div class="card-body">
            <?php if ($this->session->flashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i>
                    <?= $this->session->flashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    <?= $this->session->flashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Servis</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Kendaraan</th>
                            <th>Total Biaya</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($services)) : ?>
                            <?php foreach ($services as $index => $service) : ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= $service->service_number ?></td>
                                    <td><?= date('d/m/Y', strtotime($service->created_at)) ?></td>
                                    <td><?= $service->customer_name ?></td>
                                    <td><?= $service->vehicle_brand . ' ' . $service->vehicle_model . ' (' . $service->vehicle_year . ')' ?></td>
                                    <td>Rp <?= number_format($service->total_cost, 0, ',', '.') ?></td>
                                    <td>
                                        <?php
                                        $status_badges = [
                                            'pending' => ['class' => 'secondary', 'text' => 'Pending'],
                                            'in_progress' => ['class' => 'warning', 'text' => 'Proses'],
                                            'completed' => ['class' => 'success', 'text' => 'Selesai'],
                                            'cancelled' => ['class' => 'danger', 'text' => 'Dibatalkan']
                                        ];
                                        $status = strtolower($service->status);
                                        $badge = isset($status_badges[$status]) ? $status_badges[$status] : ['class' => 'secondary', 'text' => 'Tidak Diketahui'];
                                        ?>
                                        <span class="badge bg-<?= $badge['class'] ?>"><?= $badge['text'] ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <?php if ($service->status != 'completed') : ?>
                                                <form action="<?= base_url('service/update_status/' . $service->id) ?>" method="post" class="d-inline">
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" class="btn btn-success btn-sm" 
                                                            onclick="return confirm('Apakah Anda yakin ingin menyelesaikan servis ini?')"
                                                            data-bs-toggle="tooltip"
                                                            title="Selesaikan Servis">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            <a href="<?= base_url('service/view/' . $service->id) ?>" 
                                               class="btn btn-info btn-sm"
                                               data-bs-toggle="tooltip"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('service/edit/' . $service->id) ?>" 
                                               class="btn btn-warning btn-sm"
                                               data-bs-toggle="tooltip"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?= base_url('service/delete/' . $service->id) ?>" 
                                                  method="post" 
                                                  class="d-inline" 
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                <button type="submit" 
                                                        class="btn btn-danger btn-sm"
                                                        data-bs-toggle="tooltip"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data servis</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Required JavaScript -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    $('#dataTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json'
        },
        responsive: true,
        order: [[2, 'desc']] // Sort by date column descending
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto close alerts
    document.querySelectorAll('.alert').forEach(function(alert) {
        setTimeout(function() {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 3000);
    });
});
</script>

<?php $this->load->view('templates/footer'); ?> 
