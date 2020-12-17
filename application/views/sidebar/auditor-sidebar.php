<li class="nav-header">ส่วนผู้ตรวจ</li>
<li class="nav-item">
    <a href="<?= site_url('auditor/index') ?>" id="auditor-user-index" class="nav-link">
        <i class="nav-icon fas fa-home"></i>
        <p>หน้าหลัก</p>
    </a>
</li>
<li class="nav-item has-treeview" id="auditor-inspection-section">
    <a href="#" class="nav-link" id="auditor-inspection-subject">
        <i class="nav-icon fas fa-file-signature"></i>
        <p>
            การตรวจราชการ
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="<?= site_url('auditor/calendar') ?>" id="auditor-calendar" class="nav-link">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <p>ปฏิทินการตรวจราชการ</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item has-treeview" id="auditor-report-section">
    <a href="#" class="nav-link" id="auditor-report-subject">
        <i class="nav-icon fas fa-users"></i>
        <p>
            รายงานผลการตรวจ
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="<?= site_url('admin/index') ?>" id="" class="nav-link">
                <i class="nav-icon fas fa-house-user"></i>
                <p>หน้าหลัก</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item has-treeview">
    <a href="#" class="nav-link" id="auditor-summary-subject">
        <i class="nav-icon fas fa-users"></i>
        <p>
            สถิติผลการตรวจ
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="<?= site_url('admin/index') ?>" id="" class="nav-link">
                <i class="nav-icon fas fa-house-user"></i>
                <p>หน้าหลัก</p>
            </a>
        </li>
    </ul>
</li>