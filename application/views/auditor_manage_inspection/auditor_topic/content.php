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
                        <button class="btn btn-sm btn-success" >เพิ่มชุดตรวจ</button>
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
        <!-- CREATE PALN Modal -->
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
                        <form>
                            <div class="form-group">
                                <label>หน่วย:</label>
                                <input type="text" class="form-control" id="search-units" placeholder="ค้นหาหน่วย">
                                <select class="form-control" id="nprt-units" required>
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>วันเริ่มต้น</label>
                                <input type="date" class="form-control" id="date-start" required>
                            </div>
                            <div class="form-group">
                                <label>วันสิ้นสุด</label>
                                <input type="date" class="form-control" id="date-end" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END CREATE PALN Modal -->
    </div>
</div>