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
                    <div class="h3">สรุปผลการตรวจราชการ (<?= "{$teamPlan['TEAM_NAME']}  ปี {$teamPlan['TEAM_YEAR']}" ?>)</div>
                    <u class="h4 d-block"><?= $planDetail['NPRT_ACM'] ?></u>
                    <small class="text-muted d-block"><?= $planDetail['NPRT_NAME'] ?></small>
                    <small class="text-danger d-block">ห้วงวันที่: <?= "{$planDetail['INS_DATE']} ถึง {$planDetail['FINISH_DATE']}" ?></small>
                </div>
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <div class="card-text">
                        <div>
                            <!-- <button class="btn btn-sm btn-primary" id="create-summary-btn">เพิ่มรายการสรุปผล</button> -->
                            <button class="btn btn-sm btn-light" onclick="return window.history.back();">ย้อนกลับ</button>
                        </div>
                        <div id="loading-table">Loading data...</div>
                        <div>
                            <table class="table" id="summary-table">
                                <thead>
                                    <tr>
                                        <th class="text-center">ลำดับ</th>
                                        <th>สายการตรวจ</th>
                                        <th>คะแนน</th>
                                        <th>เวลา</th>
                                        <th class="text-center">รายละเอียด</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="h3">สรุปผลคะแนน</div>
                    <u class="h4 d-block"><?= $planDetail['NPRT_ACM'] ?></u>
                    <small class="text-muted d-block"><?= $planDetail['NPRT_NAME'] ?></small>
                    <small class="text-danger d-block">ห้วงวันที่: <?= "{$planDetail['INS_DATE']} ถึง {$planDetail['FINISH_DATE']}" ?></small>
                </div>
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <div class="card-text">
                        <div class="mb-2">
                            <div class="mb-2">
                                <div>กรอกคะแนนสรุปผล</div>
                                <div>
                                    <form id="set-plan-score" data-team-plan-id="<?= $teamPlan['ROW_ID'] ?>">
                                        <div class="form-row">
                                            <div class="form-group col-md-5">
                                                <label>ผู้บัญชาการหน่วย</label>
                                                <input type="text" class="form-control" name="commander" value="<?= $teamPlan['COMMANDER'] ?>">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-5">
                                                <label>ผลการปฏิบัติงานตามนโยบาย ผบช.</label>
                                                <input type="text" class="form-control" name="policyScore" value="<?= $teamPlan['POLICY_SCORE'] ?>">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-5">
                                                <label>ความพร้อมในการเตรียมการรับตรวจ</label>
                                                <input type="text" class="form-control" name="prepareScore" value="<?= $teamPlan['PREPARE_SCORE'] ?>">
                                            </div>
                                        </div>
                                        <div id="set-plan-score-result"></div>
                                        <button type="submit" class="btn btn-primary">บันทึก</button>
                                        <a href="<?= site_url('auditor/inspection_summary_report?team_plan_id=' . $teamPlan['ROW_ID']) ?>" target="_blank" class="btn btn-info" id="print-summary">PDF</a>
                                    </form>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <span>ผลการประเมินแต่ละสายงานเฉลี่ย: (<?= $sumScore['SCORE'] ?> *0.8) =</span>
                                    <span id="sum-score-avg"><?= $sumScore['SCORE'] * 0.8 ?></span> คะแนน
                                </div>
                                <div>
                                    <span>ผลการปฏิบัติงานตามนโยบาย ผบช.:</span>
                                    <span id="policy-score-avg"><?= $teamPlan['POLICY_SCORE'] * 0.1 ?></span> คะแนน
                                </div>
                                <div>
                                    <span>ความพร้อมในการเตรียมการรับตรวจ:</span>
                                    <span id="prepare-score-avg"><?= $teamPlan['PREPARE_SCORE'] * 0.1 ?></span> คะแนน
                                </div>
                                <div>
                                    <span>รวม:</span>
                                    <span id="all-score"></span> คะแนน
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div>
        <!-- ADD SUMMARY Modal -->
        <div class="modal fade" id="create-summary-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">เพิ่มรายการสรุปผล</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="create-summary-form" data-plan-id="<?= $teamPlan['ROW_ID'] ?>">
                            <div class="form-group">
                                <label>สายการตรวจ
                                    <span id="create-summary-form-inspection-label"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label>รายละเอียด</label>
                                <textarea class="form-control" id="create-summary-form-commention" rows="20" name="comment"></textarea>
                            </div>
                            <div id="create-summary-result"></div>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <!-- END ADD SUMMARY Modal -->

        <!-- UPDATE SUMMARY Modal -->
        <div class="modal fade" id="update-summary-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">แก้ไขรายการสรุปผล</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="update-summary-form">
                            <div class="form-group">
                                <label>สายการตรวจ
                                    <span id="update-summary-form-inspection-label"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label>รายละเอียด</label>
                                <textarea class="form-control" name="comment" rows="20" id="update-summary-comment"></textarea>
                            </div>
                            <div id="update-summary-result"></div>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <!-- UPDATE ADD SUMMARY Modal -->
    </div>
</div>