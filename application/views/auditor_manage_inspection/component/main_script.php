<!-- jQuery -->
<script src="<?= base_url('assets/admin_lte/plugins/jquery/jquery.min.js') ?>"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= base_url('assets/admin_lte/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('assets/admin_lte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- daterangepicker -->
<script src="<?= base_url('assets/admin_lte/plugins/moment/moment.min.js') ?>"></script>
<script src="<?= base_url('assets/admin_lte/plugins/daterangepicker/daterangepicker.js') ?>"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?= base_url('assets/admin_lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
<!-- Summernote -->
<!-- <script src="<?= base_url('assets/admin_lte/plugins/summernote/summernote-bs4.min.js') ?>"></script> -->
<!-- overlayScrollbars -->
<script src="<?= base_url('assets/admin_lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('assets/admin_lte/dist/js/adminlte.js') ?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url('assets/admin_lte/dist/js/demo.js') ?>"></script>
<!-- DataTables -->
<script src="<?= base_url('assets/admin_lte/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/admin_lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/admin_lte/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/admin_lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>

<?= isset($custom) ? $custom : ''?>