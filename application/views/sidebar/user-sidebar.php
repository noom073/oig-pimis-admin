<li class="nav-header">ส่วนหน่วยผู้ใช้</li>
<li class="nav-item">
    <a href="<?= site_url('user/index') ?>" id="user-index" class="nav-link">
        <i class="nav-icon fas fa-house-user"></i>
        <p>หน้าหลัก</p>
    </a>
</li>
<li class="nav-item has-treeview" id="user-section">
    <a href="#" class="nav-link" id="user-inspection-subject">
        <i class="nav-icon fas fa-users"></i>
        <p>
            การตรวจราชการ
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="<?= site_url('user/calendar') ?>" id="user-calendar" class="nav-link">
                <i class="nav-icon fas fa-user-friends"></i>
                <p>ปฏิทินการตรวจราชการ</p>
            </a>
        </li>
    </ul>
</li>