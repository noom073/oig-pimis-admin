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
                    <div class="h3">สรุปผลการตรวจราชการ</div>
                    <div class="h4">
                        <u><?= $plan['STANDFOR'] ?></u>
                    </div>
                    <div>
                        <small class="text-danger">ห้วงวันที่:
                            <?= date("d/m/Y", strtotime($plan['INS_DATE'])) ?>
                            -
                            <?= date("d/m/Y", strtotime($plan['FINISH_DATE'])) ?>
                        </small>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <div class="card-text">
                        <div>
                            <button class="btn btn-sm btn-primary" id="create-summary-btn">เพิ่มรายการสรุปผล</button>
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
                    <div class="h4">
                        <u><?= $plan['STANDFOR'] ?></u>
                    </div>
                    <div>
                        <small class="text-danger">ห้วงวันที่:
                            <?= date("d/m/Y", strtotime($plan['INS_DATE'])) ?>
                            -
                            <?= date("d/m/Y", strtotime($plan['FINISH_DATE'])) ?>
                        </small>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <div class="card-text">
                        <div class="mb-2">
                            <div class="mb-2">
                                <div>กรอกคะแนนสรุปผล</div>
                                <div>
                                    <form id="set-plan-score" data-plan-id="<?=$plan['ID']?>">
                                        <div class="form-row">
                                            <div class="form-group col-md-5">
                                                <label>ผลการปฏิบัติงานตามนโยบาย ผบช.</label>
                                                <input type="text" class="form-control" name="policyScore" value="<?=$plan['POLICY_SCORE']?>">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-5">
                                                <label>ความพร้อมในการเตรียมการรับตรวจ</label>
                                                <input type="text" class="form-control" name="prepareScore" value="<?=$plan['PREPARE_SCORE']?>">
                                            </div>
                                        </div>
                                        <div id="set-plan-score-result"></div>
                                        <button type="submit" class="btn btn-primary">บันทึก</button>
                                    </form>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <span>ผลการประเมินแต่ละสายงานเฉลี่ย: (<?= $sumScore['SCORE']?> *0.8) =</span>
                                    <span id="sum-score-avg"><?= $sumScore['SCORE']*0.8 ?></span> คะแนน
                                </div>
                                <div>
                                    <span>ผลการปฏิบัติงานตามนโยบาย ผบช.:</span>
                                    <span id="policy-score-avg"><?=$plan['POLICY_SCORE']*0.1?></span> คะแนน
                                </div>
                                <div>
                                    <span>ความพร้อมในการเตรียมการรับตรวจ:</span>
                                    <span id="prepare-score-avg"><?=$plan['PREPARE_SCORE']*0.1?></span> คะแนน
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
        <!-- Modal -->
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
                        <form id="create-summary-form" data-plan-id="<?= $plan['ID'] ?>">
                            <div class="form-group">
                                <label>สายการตรวจ</label>
                                <select class="form-control" name="inspectionID" id="create-summary-inspections"></select>
                            </div>
                            <div class="form-group">
                                <label>รายละเอียด</label>
                                <textarea class="form-control" name="comment"></textarea>
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
    </div>
</div>