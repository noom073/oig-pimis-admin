<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= site_url() ?>" class="brand-link">
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="OIG-PIMIS Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">OIG-PIMIS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= base_url('assets/admin_lte/dist/img/user2-160x160.jpg') ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    <div><?= $name ?></div>
                    <div class="text-sm">
                        <?php
                        foreach ($this->session->usertype as $r) {
                            echo "<div>{$r['TYPE_NAME_FULL']}</div>";
                        }
                        ?>
                    </div>
                </a>
                <div class="text-sm text-light"><a href="<?=site_url('welcome/logout')?>">Logout</a></div>

            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item"></li>
                <?php
                foreach ($this->session->usertype as $r) {
                    if ($r['TYPE_NAME'] == 'admin') $this->load->view('sidebar/admin-sidebar');
                    if ($r['TYPE_NAME'] == 'control') $this->load->view('sidebar/controller-user-sidebar');
                    if ($r['TYPE_NAME'] == 'auditor') $this->load->view('sidebar/auditor-sidebar');
                    if ($r['TYPE_NAME'] == 'viewer') $this->load->view('sidebar/viewer-sidebar');
                    if ($r['TYPE_NAME'] == 'user') $this->load->view('sidebar/user-sidebar');
                }
                ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>