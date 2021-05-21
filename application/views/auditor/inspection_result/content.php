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
                    <div class="h3">รายการที่บันทึกการตรวจราชการ (<?= "{$teamPlan['TEAM_NAME']}  ปี {$teamPlan['TEAM_YEAR']}" ?>)</div>
                    <u class="h4 d-block"><?= $planDetail['NPRT_ACM'] ?></u>
                    <small class="text-muted d-block"><?= $planDetail['NPRT_NAME'] ?></small>
                    <small class="text-danger d-block">ห้วงวันที่: <?= "{$planDetail['INS_DATE']} ถึง {$planDetail['FINISH_DATE']}" ?></small>
                </div>
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <div class="card-text">
                        <div>
                            <button class="btn btn-sm btn-light" onclick="return window.history.back();">ย้อนกลับ</button>
                            <button class="btn btn-sm btn-primary" id="add-note-btn">เพิ่มบันทึก</button>
                        </div>
                        <div>
                            <table class="table" id="note-table">
                                <thead>
                                    <tr>
                                        <th class="text-center">ลำดับ</th>
                                        <th class="text-center">สายการตรวจ</th>
                                        <th class="text-center">เวลา</th>
                                        <th class="text-center">link</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CREATE NOTE Modal -->
    <div class="modal fade" id="create-note-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มบันทึก</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="create-note-form">
                        <div class="form-group">
                            <label for="exampleInputEmail1">เรื่อง</label>
                            <select class="form-control" name="inspectionOptionID" required>
                                <option value="">โปรดระบุ</option>
                                <?php foreach ($teamInspections as $teamInspection) { ?>
                                    <option value="<?= $teamInspection['INSPECTION_OPTION_ID'] ?>"><?= $teamInspection['INSPECTION_NAME'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>หน่วยรับตรวจ</label>
                                <div class="form-control"><?= $planDetail['NPRT_ACM'] ?></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>ผบ.หน่วย</label>
                                <input class="form-control" type="text" name="commander" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>ตรวจเมื่อวันที่</label>
                                <input class="form-control" type="date" name="dateTime" value="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>ผู้รับตรวจ</label>
                                <input class="form-control" type="text" name="auditee" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>ตำแหน่ง</label>
                                <input class="form-control" type="text" name="auditeePosition" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>ผู้ตรวจ</label>
                                <div class="form-control"><?= $name ?></div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>ข้อควรแก้ไขและข้อบกพร่องที่ตรวจพบ</label>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label>1. ข้อควรแก้ไข</label>
                            <textarea class="form-control" name="canImprove" cols="30" rows="10"></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label>2. ข้อบกพร่อง</label>
                            <textarea class="form-control" name="failing" cols="30" rows="10"></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label>3. ข้อบกพร่องสำคัญมาก</label>
                            <textarea class="form-control" name="importantFailing" cols="30" rows="10"></textarea>
                        </div>

                        <div class="form-group col-md-12">
                            <label>4. ข้อสังเกตจากการตรวจและข้อแนะนำ</label>
                            <textarea class="form-control" name="commention" cols="30" rows="10"></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>ผลคะแนนตามสายงาน</label>
                                <input class="form-control" type="text" name="inspectioScore">
                            </div>
                            <div class="form-group col-md-12">
                                <label>คะแนนประสิทธิภาพในการปฏิบัติงาน</label>
                                <input class="form-control" type="text" name="workingScore">
                            </div>
                        </div>

                        <div id="create-note-form-result"></div>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END CREATE NOTE Modal -->

    <!-- EDIT NOTE Modal -->
    <div class="modal fade" id="edit-note-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มบันทึก</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit-note-form">
                        <div class="form-group">
                            <label for="exampleInputEmail1">เรื่อง</label>
                            <select class="form-control" name="inspectionOptionID" id="edit-note-form-inspection-option">
                                <?php foreach ($teamInspections as $teamInspection) { ?>
                                    <option value="<?= $teamInspection['INSPECTION_OPTION_ID'] ?>"><?= $teamInspection['INSPECTION_NAME'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>หน่วยรับตรวจ</label>
                                <div class="form-control"><?= $planDetail['NPRT_ACM'] ?></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>ผบ.หน่วย</label>
                                <input class="form-control" id="edit-note-form-commander" type="text" name="commander" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>ตรวจเมื่อวันที่</label>
                                <input class="form-control" id="edit-note-form-date" type="date" name="dateTime" value="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>ผู้รับตรวจ</label>
                                <input class="form-control" id="edit-note-form-auditee" type="text" name="auditee" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>ตำแหน่ง</label>
                                <input class="form-control" id="edit-note-form-auditee-position" type="text" name="auditeePosition" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>ผู้ตรวจ</label>
                                <div class="form-control"><?= $name ?></div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>ข้อควรแก้ไขและข้อบกพร่องที่ตรวจพบ</label>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label>1. ข้อควรแก้ไข</label>
                            <textarea class="form-control" id="edit-note-form-can-improve" name="canImprove" cols="30" rows="10"></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label>2. ข้อบกพร่อง</label>
                            <textarea class="form-control" id="edit-note-form-failing" name="failing" cols="30" rows="10"></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label>3. ข้อบกพร่องสำคัญมาก</label>
                            <textarea class="form-control" id="edit-note-form-important-failing" name="importantFailing" cols="30" rows="10"></textarea>
                        </div>

                        <div class="form-group col-md-12">
                            <label>4. ข้อสังเกตจากการตรวจและข้อแนะนำ</label>
                            <textarea class="form-control" id="edit-note-form-commention" name="commention" cols="30" rows="10"></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>ผลคะแนนตามสายงาน</label>
                                <input class="form-control" id="edit-note-form-inspection-score" type="text" name="inspectioScore">
                            </div>
                            <div class="form-group col-md-12">
                                <label>คะแนนประสิทธิภาพในการปฏิบัติงาน</label>
                                <input class="form-control" id="edit-note-form-working-score" type="text" name="workingScore">
                            </div>
                        </div>
                        <div id="edit-note-form-result"></div>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END EDIT NOTE Modal -->
</div>