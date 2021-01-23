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
                        <li class="breadcrumb-item active">การจัดการสายการตรวจ</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="h3">การจัดการสายการตรวจ</div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <button class="btn btn-sm btn-light" onclick="return window.history.back();">ย้อนกลับ</button>
                        <button class="btn btn-sm btn-success" id="create-auditor-type">เพิ่มประเภทผู้ตรวจ</button>
                    </div>
                    <div class="card-text">
                        <div>
                            <table id="auditor-type-table" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">ลำดับ</th>
                                        <th class="text-center">ประเภทผู้ตรวจ</th>
                                        <th class="text-center">สายการตรวจ</th>
                                        <th class="text-center">การดำเนินการ</th>
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
        <!-- ADD AUDITOR TYPE Modal -->
        <div class="modal fade" id="create-auditor-type-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">เพิ่มประเภทผู้ตรวจ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="create-auditor-type-form">
                            <div class="form-group">
                                <label>ชื่อประเภทผู้ตรวจ</label>
                                <input type="text" class="form-control" name="inspectionName" required>
                            </div>

                            <div class="form-group">
                                <label>ประเภทสายการตรวจ</label>
                                <select name="insoectionType" class="form-control" id="create-auditor-type-form-inspection-type"></select>
                            </div>

                            <div id="create-auditor-type-form-result"></div>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <!-- END ADD AUDITOR TYPE Modal -->

        <!-- EDIT AUDITOR TYPE Modal -->
        <div class="modal fade" id="edit-auditor-type-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">แก้ไขประเภทผู้ตรวจ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="edit-auditor-type-form">
                            <div class="form-group">
                                <label>ชื่อประเภทผู้ตรวจ</label>
                                <input type="text" class="form-control" name="inspectionName" id="edit-auditor-type-form-name" required>
                            </div>

                            <div class="form-group">
                                <label>ประเภทสายการตรวจ</label>
                                <select name="insoectionType" class="form-control" id="edit-auditor-type-form-inspection-type"></select>
                            </div>

                            <div id="edit-auditor-type-form-result"></div>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <!-- END EDIT AUDITOR TYPE Modal -->

    </div>
</div>