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
                            <button class="btn btn-sm btn-danger close-window">ปิดหน้าต่างนี้</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Controller User</h5>
                    <div class="card-text">
                        <div class="mt-3">
                            <label>รายการคำถาม ของ <i>หัวข้อ <?= $subject['SUBJECT_NAME'] ?></i></label>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-primary add-question">เพิ่มคำถาม</button>
                            <div>
                                <small class="text-danger">
                                    <b>* ข้อควรระวัง :</b>
                                    <u>กรณีที่มีการกรอกคำตอบแล้ว การเปลี่ยนปลงคำถาม อาจทำให้ผลสรุปคลาดเคลื่อนได้</u>
                                </small>
                            </div>
                        </div>
                        <div class="mt-2" id="question-list"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div>
        <!-- ADD QUESTION Modal -->
        <div class="modal fade" id="add-question-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">เพิ่มคำถาม</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <strong class="text-info">หัวข้อ<?= $subject['SUBJECT_NAME'] ?></strong>
                        </div>
                        <form id="create-question-form" data-subject-id="<?= $subject['SUBJECT_ID'] ?>">
                            <div class="form-group">
                                <label>ชื่อคำถาม</label>
                                <input type="text" class="form-control" name="questionName" required>
                            </div>

                            <div class="form-group">
                                <label>ลำดับ</label>
                                <input type="number" class="form-control" name="questionOrder" required>
                            </div>

                            <div id="result-create-question-form"></div>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <!-- END ADD QUESTION Modal -->

        <!-- EDIT QUESTION Modal -->
        <div class="modal fade" id="edit-question-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">แก้ไขคำถาม</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <strong class="text-info">หัวข้อ<?= $subject['SUBJECT_NAME'] ?></strong>
                        </div>
                        <form id="edit-question-form">
                            <div class="form-group">
                                <label>ชื่อคำถาม</label>
                                <input type="text" class="form-control" name="questionName" id="question-name-edit-question-form" required>
                            </div>

                            <div class="form-group">
                                <label>ลำดับ</label>
                                <input type="number" class="form-control" name="questionOrder" id="question-order-edit-question-form" required>
                            </div>

                            <div id="result-edit-question-form"></div>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <!-- END EDIT QUESTION Modal -->
    </div>
</div>