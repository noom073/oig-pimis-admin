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
                        <button class="btn btn-sm btn-success" id="create-inspection">เพิ่มสายการตรวจ</button>
                    </div>
                    <div class="card-text">
                        <div>
                            <table id="inspection-table" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">ลำดับ</th>
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
                                <label>ชื่อสายการตรวจ</label>
                                <input type="text" class="form-control" name="inspectionName" id="inspection-name-create-inspection-form" required>
                            </div>

                            <div class="form-group">
                                <label>ลำดับสายการตรวจ</label>
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

        <!-- EDIT INSPECTION Modal -->
        <div class="modal fade" id="edit-inspection-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">เพิ่มสายการตรวจ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="edit-inspection-form">
                            <div class="form-group">
                                <label>ชื่อสายการตรวจ</label>
                                <input type="text" class="form-control" name="inspectionName" id="inspection-name-edit-inspection-form" required>
                            </div>

                            <div class="form-group">
                                <label>ลำดับสายการตรวจ</label>
                                <input type="number" min="1" class="form-control" name="inspectionOrder" id="inspection-order-edit-inspection-form" required>
                            </div>

                            <div id="result-edit-inspection-form"></div>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <!-- END EDIT INSPECTION Modal -->
    </div>
</div>