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