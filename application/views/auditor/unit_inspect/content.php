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
                                            <button type="button" class="list-group-item list-group-item-action inspect-list" data-inspection-option-id="<?= $inspection['INSPECTION_OPTION_ID'] ?>">
                                                <?= $inspection['INSPECTION_NAME'] ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div>
                            <div class="mb-3">
                                <div id="form-loading" class="d-none">Loading data...</div>
                                <form id="user-evaluate-form" class="d-none" data-team-plan-id="<?= $teamPlan['ROW_ID'] ?>">
                                    <h5>ฟอร์ม</h5>
                                    <div id="form-questionaire"></div>
                                    <div>
                                        <!-- <button class="btn btn-sm btn-primary" id="user-evaluate-form-submit">บันทึก</button>
                                        <button class="btn btn-sm btn-light" type="button">ปิด</button> -->
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

    <div>
        <!-- USER PUT ITEM TO EVALUATE Modal -->
        <div class="modal fade" id="evaluate-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="evaluate-modal-label"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="evaluate-form" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>การปฏิบัติ</label>
                                <div>
                                    <div id="unit-inspect-status"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mt-2">
                                    <label>รายการไฟล์แนบ:</label>
                                    <div id="list-files"></div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <!-- END USER PUT ITEM TO EVALUATE Modal -->
    </div>
</div>