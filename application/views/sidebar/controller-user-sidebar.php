<li class="nav-header">ส่วนผู้จัดการข้อมูล</li>
<li class="nav-item">
    <a href="<?= site_url('controller_user/index') ?>" id="controller-user-index" class="nav-link">
        <i class="nav-icon fas fa-home"></i>
        <p>หน้าหลัก</p>
    </a>
</li>
<li class="nav-item has-treeview" id="controller-user-headnav-manage-data">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-database"></i>
        <p>
            การจัดการข้อมูล
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="<?= site_url('controller_user/inspection_list') ?>" id="controller-user-inspection" class="nav-link">
                <i class="nav-icon far fa-question-circle"></i>
                <p>การจัดการสายการตรวจ</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= site_url('controller_user/subject') ?>" id="controller-user-inspection-option" class="nav-link">
                <i class="nav-icon far fa-question-circle"></i>
                <p title="การจัดการประเภทสายการตรวจ">การจัดการประเภทฯ</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item has-treeview" id="controller-user-headnav-summarize">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-chart-line"></i>
        <p>
            ข้อมูลสรุป
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="#" id="controller-user-xxx" class="nav-link">
                <i class="nav-icon far fa-file-alt"></i>
                <p>ดูรายงานผลการตรวจฯ</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" id="controller-user-xxxx" class="nav-link">
                <i class="nav-icon far fa-chart-bar"></i>
                <p>ดูสถิติผลการตรวจฯ</p>
            </a>
        </li>
    </ul>
</li>