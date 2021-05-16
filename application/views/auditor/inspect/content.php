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
                    <div class="h3">การตรวจราชการ (<?= "{$teamPlan['TEAM_NAME']}  ปี {$teamPlan['TEAM_YEAR']}" ?>)</div>
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
                        <div class="row mb-2">
                            <?php foreach ($inspections as $inspection) { ?>
                                <div class="col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <?php if ($inspection['INSPECTED'] == null) { ?>
                                                <button type="button" class="list-group-item list-group-item-action inspect-list" data-inspection-option-id="<?= $inspection['INSPECTION_OPTION_ID'] ?>">
                                                    <?= $inspection['INSPECTION_NAME'] ?>
                                                </button>
                                            <?php } else { ?>
                                                <a href="<?= site_url("auditor/inspected?teamPlanID={$teamPlan['ROW_ID']}&inspectionOptionID={$inspection['INSPECTION_OPTION_ID']}") ?>" class="list-group-item list-group-item-action" data-inspection-option-id="<?= $inspection['INSPECTION_OPTION_ID'] ?>">
                                                    <?= $inspection['INSPECTION_NAME'] ?>
                                                </a>
                                                <small class="mt-2 d-block text-success text-right">บันทึกข้อมูลแล้ว</small>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div>
                            <div class="mb-3">
                                <div id="form-loading" class="d-none">Loading data...</div>
                                <form id="auditor-inspect-form" class="d-none" data-team-plan-id="<?= $teamPlan['ROW_ID'] ?>">
                                    <h5>ชุดคำถาม</h5>
                                    <div id="form-questionaire"></div>
                                    <div>
                                        <button class="btn btn-sm btn-primary d-none" id="auditor-inspect-form-submit" tabindex="-1">บันทึก</button>
                                    </div>
                                    <div id="result-auditor-score" class="invisible">
                                        รวม <span id="total-auditor-score"></span>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>