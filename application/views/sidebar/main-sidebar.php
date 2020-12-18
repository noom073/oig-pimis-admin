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
                        foreach ($userType as $r) {
                            echo "<div>{$r}</div>";
                        }
                        ?>
                    </div>
                </a>
                <div class="text-sm text-light"><a href="#">Logout</a></div>

            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item"></li>
                <?php
                if (in_array('Administrator', $userType)) $this->load->view('sidebar/admin-sidebar');
                if (in_array('Controller', $userType)) $this->load->view('sidebar/controller-user-sidebar');
                if (in_array('Auditor', $userType)) $this->load->view('sidebar/auditor-sidebar');
                if (in_array('Viewer', $userType)) $this->load->view('sidebar/viewer-sidebar');
                if (in_array('User', $userType)) $this->load->view('sidebar/user-sidebar');
                ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>