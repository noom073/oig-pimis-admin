<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Controller User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url('controller_user') ?>">Controller User</a></li>
                        <li class="breadcrumb-item active">การจัดการคำถามประเมิน</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <div class="h3">การจัดการคำถามประเมิน</div>
                        <div class="ml-auto">
                            <button class="btn btn-sm btn-danger close-window">ปิดแทบ</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Controller User</h5>
                    <div class="card-text">
                        <div class="mt-3">
                            <label>รายการคำถาม ของ <i>หัวข้อ<?= $subject['SUBJECT_NAME'] ?></i></label>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-primary">เพิ่มคำถาม</button>
                        </div>
                        <div class="mt-2">
                            <?php foreach ($questions as $k => $val) { ?>
                                <?php $num = $k + 1; ?>
                                <div class="d-flex mb-1">
                                    <div>
                                        <span><?= $num ?>.</span>
                                        <span><?= $val['Q_NAME'] ?></span>
                                    </div>
                                    <div class="ml-auto">
                                        <button class="btn btn-sm btn-primary" data-question-id="<?= $val['Q_ID'] ?>">แก้ไข</button>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>