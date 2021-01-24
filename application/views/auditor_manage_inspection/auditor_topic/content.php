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
                        <li class="breadcrumb-item active">รายการชุดตรวจ</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="h3">รายการชุดตรวจ</div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <button class="btn btn-sm btn-light" onclick="return window.history.back();">ย้อนกลับ</button>
                        <button class="btn btn-sm btn-success" id="add-auditor-team">เพิ่มชุดตรวจ</button>
                    </div>
                    <h5 class="card-title"></h5>
                    <div class="card-text">
                        <div>
                            <table id="auditor-team-table" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">ลำดับ</th>
                                        <th class="text-center">ชื่อสาย</th>
                                        <th class="text-center">ปี</th>
                                        <th class="text-center">สี</th>
                                        <th class="text-center">รายละเอียด</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div>
        <!-- CREATE AUDITOR NAME Modal -->
        <div class="modal fade" id="create-auditor-name-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">กำหนดชื่อชุดตรวจ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="create-auditor-name-form">
                            <div class="form-group">
                                <label>ชื่อชุดตรวจ:</label>
                                <input type="text" class="form-control" name="teamName" placeholder="ชื่อชุดตรวจ" required>
                            </div>
                            <div class="form-group">
                                <label>ประจำปี</label>
                                <select class="form-control" name="teamYear" required>
                                    <?php for ($i = 0; $i < 3; $i++) { ?>
                                        <option value="<?= date("Y") + 544 - $i ?>"><?= date("Y") + 544 - $i ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>เลือกสี:</label>
                                <input type="color" class="form-control" name="color" value="#ffffff" required>
                            </div>
                            <div id="create-auditor-name-form-result"></div>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END CREATE AUDITOR NAME Modal -->

        <!-- EDIT AUDITOR NAME Modal -->
        <div class="modal fade" id="edit-auditor-name-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">แก้ไขชื่อชุดตรวจ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="edit-auditor-name-form">
                            <div class="form-group">
                                <label>ชื่อชุดตรวจ:</label>
                                <input type="text" class="form-control" name="teamName" id="edit-auditor-name-form-team-name" placeholder="ชื่อชุดตรวจ" required>
                            </div>
                            <div class="form-group">
                                <label>ประจำปี</label>
                                <select class="form-control" name="teamYear" id="edit-auditor-name-form-team-year" required>
                                    <?php for ($i = 0; $i < 3; $i++) { ?>
                                        <option value="<?= date("Y") + 544 - $i ?>"><?= date("Y") + 544 - $i ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>เลือกสี:</label>
                                <input type="color" class="form-control" name="color" id="edit-auditor-name-form-team-color" required>
                            </div>
                            <div id="edit-auditor-name-form-result"></div>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END EDIT AUDITOR NAME Modal -->
    </div>
</div>