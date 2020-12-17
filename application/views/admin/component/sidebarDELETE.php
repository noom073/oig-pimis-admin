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
                    <div class="text-sm"><?= $userType ?></div>
                </a>
                <div class="text-sm text-light"><a href="#">Logout</a></div>

            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-header">ส่วนจัดการผู้ใช้</li>
                <li class="nav-item">
                    <a href="<?= site_url('admin/index') ?>" id="admin-index" class="nav-link">
                        <i class="nav-icon fas fa-house-user"></i>
                        <p>หน้าหลัก</p>
                    </a>
                </li>
                <li class="nav-item has-treeview" id="admin-manage-user-section">
                    <a href="#" class="nav-link" id="admin-manage-user-subject">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            การจัดการผู้ใช้
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">                        
                        <li class="nav-item">
                            <a href="<?= site_url('admin/list_user') ?>" id="admin-list-user" class="nav-link">
                                <i class="nav-icon fas fa-user-friends"></i>
                                <p>รายชื่อผู้ใช้</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>