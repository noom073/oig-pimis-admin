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
                    <div class="h3">ชุดคำถามประเมิน <?= "{$insOpt['INSPECTION_NAME']} ({$insOpt['OPTION_YEAR']})" ?></div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <button class="btn btn-sm btn-light" onclick="return window.history.back();">ย้อนกลับ</button>
                    </div>
                    <div class="card-text">
                        <div class="my-2">
                            <button class="btn btn-sm btn-success subject-activity-btn" id="add-subject">เพิ่มหัวข้อการตรวจ</button>
                            <button class="btn btn-sm btn-primary subject-activity-btn" id="collapse-subject-ul">แสดงทั้งหมด</button>
                            <button class="btn btn-sm btn-primary subject-activity-btn" id="show-subject-ul">ปิดทั้งหมด</button>
                        </div>
                        <div>
                            <div>
                                <b>หัวข้อการตรวจ</b>
                                <div>
                                    <small class="text-danger">
                                        <b>* ข้อควรระวัง :</b>
                                        <u>กรณีที่มีการกรอกคำตอบแล้ว การเปลี่ยนปลงคำถาม อาจทำให้ผลสรุปคลาดเคลื่อนได้</u>
                                    </small>
                                </div>
                            </div>
                            <div id="tree"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div>
        <!-- ADD SUBJECT Modal -->
        <div class="modal fade" id="create-subject-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">เพิ่มหัวข้อการตรวจ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="create-subject-form">
                            <div class="form-group">
                                <label>หัวข้อการตรวจ</label>
                                <input type="text" class="form-control" name="subject_name" required>
                            </div>
                            <div class="form-group">
                                <label>ลำดับ</label>
                                <input type="number" class="form-control" name="subject_order" required>
                            </div>
                            <div id="result-create-subject-form"></div>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END ADD SUBJECT Modal -->

        <!-- EDIT SUBJECT Modal -->
        <div class="modal fade" id="edit-subject-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">แก้ไขหัวข้อการตรวจ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="edit-subject-form">
                            <div class="form-group">
                                <label>หัวข้อการตรวจ</label>
                                <input type="text" class="form-control" name="subject_name" id="subject-name-edit-subject-form" required>
                            </div>

                            <div class="form-group">
                                <label>หัวข้อการตรวจหลัก</label>
                                <select class="custom-select" name="subject_parent" id="subject-parent-edit-subject-form"></select>
                            </div>

                            <div class="form-group">
                                <label>ชุดคำถามประเมิน</label>
                                <select class="custom-select" name="inspectionID" id="inspection-id-edit-subject-form">
                                    <option value="<?= $insOpt['ROW_ID'] ?>"><?= $insOpt['INSPECTION_NAME'] ?></option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>ลำดับ</label>
                                <input type="number" class="form-control" name="subject_order" id="subject-order-edit-subject-form" required>
                            </div>

                            <div id="result-edit-subject-form"></div>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <!-- END EDIT SUBJECT Modal -->

        <!-- ADD SUB-SUBJECT Modal -->
        <div class="modal fade" id="add-sub-subject-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">เพิ่มหัวข้อการตรวจ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="create-sub-subject-form">
                            <div class="form-group">
                                <label>หัวข้อการตรวจ</label>
                                <input type="text" class="form-control" name="subjectName" id="subject-name-create-sub-subject-form" required>
                            </div>

                            <div class="form-group">
                                <label>หัวข้อการตรวจหลัก</label>
                                <select class="custom-select" name="subjectParent" id="subject-parent-create-sub-subject-form"></select>
                            </div>

                            <div class="form-group">
                                <label>ชุดคำถามประเมิน</label>
                                <select class="custom-select" name="inspectionID" id="inspection-create-sub-subject-form">
                                    <option value="<?= $insOpt['ROW_ID'] ?>"><?= $insOpt['INSPECTION_NAME'] ?></option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>ลำดับ</label>
                                <input type="number" class="form-control" name="subjectOrder" id="subject-order-create-sub-subject-form" required>
                            </div>

                            <div id="result-create-sub-subject-form"></div>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <!-- END ADD SUB-SUBJECT Modal -->

        <!-- ADD INSPECTION Modal -->
        <div class="modal fade" id="create-inspection-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">เพิ่มสายการตรวจ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="create-inspection-form">
                            <div class="form-group">
                                <label>ประเภทการตรวจ</label>
                                <input type="text" class="form-control" name="inspectionName" id="inspection-name-create-inspection-form" required>
                            </div>

                            <div class="form-group">
                                <label>ลำดับ</label>
                                <input type="number" min="1" class="form-control" name="inspectionOrder" id="inspection-order-create-inspection-form" required>
                            </div>

                            <div id="result-create-inspection-form"></div>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <!-- END ADD INSPECTION Modal -->
    </div>
</div>