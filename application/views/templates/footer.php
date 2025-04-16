        </div> <!-- End of content-wrapper -->

        <!-- Footer -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <span class="text-muted">© <?= date('Y') ?> Dealer Motor. All rights reserved.</span>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <span class="text-muted">Version 1.0.0</span>
                    </div>
                </div>
            </div>
        </footer>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- DataTables -->
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
        <!-- Select2 -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
        $(document).ready(function() {
            // Initialize DataTables with custom settings
            $('.datatable').each(function() {
                if (!$.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable({
                        responsive: true,
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                        },
                        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                             "<'row'<'col-sm-12'tr>>" +
                             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                        pageLength: 10,
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                    });
                }
            });

            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            // Confirm delete using SweetAlert2
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                const link = $(this).attr('href');
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = link;
                    }
                });
            });

            // Format currency inputs
            $('.currency-input').on('input', function() {
                let value = $(this).val().replace(/[^\d]/g, '');
                if (value !== '') {
                    value = parseInt(value).toLocaleString('id-ID');
                    $(this).val(value);
                }
            });

            // Format number inputs
            $('.number-input').on('input', function() {
                let value = $(this).val().replace(/[^\d]/g, '');
                $(this).val(value);
            });

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger: 'hover'
                });
            });

            // Initialize popovers
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });

            // Form validation
            const forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        });
        </script>
    </body>
</html> 
