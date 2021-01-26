<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Auditor</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url('auditor/index') ?>">Auditor</a></li>
                        <li class="breadcrumb-item active">การตรวจราชการ</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="h3">การตรวจราชการ</div>
                    <u class="h4 d-block"><?= $planDetail['NPRT_ACM'] ?></u>
                    <small class="text-muted d-block"><?= $planDetail['NPRT_NAME'] ?></small>
                    <small class="text-danger d-block">ห้วงวันที่: <?= "{$planDetail['INS_DATE']} ถึง {$planDetail['FINISH_DATE']}" ?></small>
                </div>
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <div class="card-text">
                        <div>
                            <button class="btn btn-sm btn-light" onclick="return window.history.back();">ย้อนกลับ</button>
                        </div>
                        <div class="pt-3">
                            <div class="row">
                                <div class="col">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h5 class="card-title"></h5>
                                            <p class="card-text">
                                                <a href="<?= site_url("auditor/inspect?plan={$teamPlan['ROW_ID']}") ?>" class="card-link">การตรวจตามสายงาน</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h5 class="card-title"></h5>
                                            <p class="card-text">
                                                <a href="<?= site_url("auditor/inspection_result?plan={$teamPlan['ROW_ID']}") ?>" class="card-link">บันทึกผลการตรวจ</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h5 class="card-title"></h5>
                                            <p class="card-text">
                                                <a href="<?= site_url("auditor/inspection_summary?plan={$teamPlan['ROW_ID']}") ?>" class="card-link">สรุปผลการตรวจ</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>