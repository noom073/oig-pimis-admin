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
                        <li class="breadcrumb-item active">สมาชิกชุดตรวจ</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="h3">สมาชิกชุดตรวจ "<?= $team['TEAM_NAME'] ?>"</div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <button class="btn btn-sm btn-light" onclick="return window.history.back();">ย้อนกลับ</button>
                        <button class="btn btn-sm btn-success" id="add-auditor-member">เพิ่มสมาชิกชุดตรวจ</button>
                    </div>
                    <h5 class="card-title"></h5>
                    <div class="card-text">
                        <div>
                            <table id="auditor-member-table" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">ลำดับ</th>
                                        <th class="text-center">ชื่อ นามสกุล</th>
                                        <th class="text-center">หน้าที่</th>
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
        <div class="modal fade" id="create-auditor-member-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">เพิ่มสมาชิกชุดตรวจ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="create-auditor-member-form">
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label>คำนำหน้าชื่อ</label>
                                    <input type="text" class="form-control" name="title" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>ชื่อ</label>
                                    <input type="text" class="form-control" name="firstName" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>นามสกุล</label>
                                    <input type="text" class="form-control" name="lastName" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>ตำแหน่ง</label>
                                    <input type="text" class="form-control" name="position" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>เลขประชาชน</label>
                                    <input type="text" class="form-control" name="idp" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>ชุดตรวจที่</label>
                                    <select class="form-control" name="auditorTeam" required>
                                        <option value="<?= $team['ROW_ID'] ?>"><?= $team['TEAM_NAME'] ?></option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>ประเภทผู้ตรวจ</label>
                                    <select class="form-control" name="auditorType" required>
                                        <?php foreach ($auditorTypes as $r) { ?>
                                            <option value="<?= $r['ADT_T_ID'] ?>"><?= $r['AUDITOR_POSITION'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div id="create-auditor-member-form-result"></div>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END CREATE AUDITOR NAME Modal -->

        <!-- EDIT AUDITOR NAME Modal -->
        <div class="modal fade" id="edit-auditor-member-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">แก้ไขสมาชิกชุดตรวจ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="edit-auditor-member-form">
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label>คำนำหน้าชื่อ</label>
                                    <input type="text" class="form-control" name="title" id="edit-auditor-member-form-title" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>ชื่อ</label>
                                    <input type="text" class="form-control" name="firstName" id="edit-auditor-member-form-first-name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>นามสกุล</label>
                                    <input type="text" class="form-control" name="lastName" id="edit-auditor-member-form-last-name" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>ตำแหน่ง</label>
                                    <input type="text" class="form-control" name="position" id="edit-auditor-member-form-position" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>เลขประชาชน</label>
                                    <input type="text" class="form-control" name="idp" id="edit-auditor-member-form-idp" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>ชุดตรวจที่</label>
                                    <select class="form-control" name="auditorTeam" id="edit-auditor-member-form-auditor-team" required>
                                        <option value="<?= $team['ROW_ID'] ?>"><?= $team['TEAM_NAME'] ?></option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>ประเภทผู้ตรวจ</label>
                                    <select class="form-control" name="auditorType" id="edit-auditor-member-form-auditor-type" required>
                                        <?php foreach ($auditorTypes as $r) { ?>
                                            <option value="<?= $r['ADT_T_ID'] ?>"><?= $r['AUDITOR_POSITION'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div id="edit-auditor-member-form-result"></div>
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