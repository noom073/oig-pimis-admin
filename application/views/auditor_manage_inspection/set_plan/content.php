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
                        <li class="breadcrumb-item active">กำหนดแผนการตรวจ</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="h3">กำหนดแผนการตรวจ</div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <button class="btn btn-sm btn-light" onclick="return window.history.back();">ย้อนกลับ</button>
                    </div>
                    <h5 class="card-title"></h5>
                    <div class="card-text">
                        <div id="load-calendar">Loading...</div>
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div>
        <!-- CREATE PLAN Modal -->
        <div class="modal fade" id="create-plan-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">กำหนดหน่วย และวันที่ออกตรวจฯ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="create-plan-form">
                            <div class="form-group">
                                <label>หน่วย:</label>
                                <input type="text" class="form-control" id="search-units" placeholder="ค้นหาหน่วย">
                                <select class="form-control" name="unitID" id="nprt-units" required></select>
                            </div>
                            <div class="form-group">
                                <label>วันเริ่มต้น</label>
                                <input type="date" class="form-control" name="startDate" id="date-start" required>
                            </div>
                            <div class="form-group">
                                <label>วันสิ้นสุด</label>
                                <input type="date" class="form-control" name="endDate" id="date-end" required>
                            </div>
                            <div class="form-group">
                                <label>ชุดตรวจ</label>
                                <select style="width: 100%;" class="form-control select2" name="auditorTeam[]" id="auditor-team" multiple required></select>
                            </div>
                            <div id="create-plan-form-result"></div>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END CREATE PLAN Modal -->

        <!-- EDIT PLAN Modal -->
        <div class="modal fade" id="edit-plan-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">กำหนดหน่วย และวันที่ออกตรวจฯ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="edit-plan-form">
                            <div class="form-group">
                                <label>หน่วย:</label>
                                <input type="text" class="form-control" id="edit-plan-modal-search-units" placeholder="ค้นหาหน่วย">
                                <select class="form-control" name="unitID" id="edit-plan-modal-nprt-units" required></select>
                            </div>
                            <div class="form-group">
                                <label>วันเริ่มต้น</label>
                                <input type="date" class="form-control" name="startDate" id="edit-plan-modal-date-start" required>
                            </div>
                            <div class="form-group">
                                <label>วันสิ้นสุด</label>
                                <input type="date" class="form-control" name="endDate" id="edit-plan-modal-date-end" required>
                            </div>
                            <div class="form-group">
                                <label>ชุดตรวจ</label>
                                <select style="width: 100%;" class="form-control select2" name="auditorTeam[]" id="edit-plan-modal-auditor-team" multiple></select>
                            </div>
                            <div id="edit-plan-form-result"></div>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END EDIT PLAN Modal -->
        
    </div>
</div>