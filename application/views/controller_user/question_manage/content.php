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
                    <div class="h3">การจัดการชุดคำถามประเมิน</div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <button class="btn btn-sm btn-light" onclick="return window.history.back();">ย้อนกลับ</button>
                        <div>
                            <button class="btn btn-sm btn-success invisible" id="add-inspection-option">
                                + เพิ่มสายการตรวจ
                                <span></span>
                            </button>
                        </div>
                    </div>
                    <div class="card-text">
                        <div>
                            <form id="get-inspections-option-form">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>ประเภทสายการตรวจ</label>
                                        <select class="custom-select" id="inspection-list" name="inspectionID"></select>
                                    </div>
                                </div>
                            </form>

                            <div id="fetch-inspection-option-loading" class="invisible">Loading...</div>
                        </div>
                        <div class="mt-3">
                            <div class="h5">ชุดคำถาม</div>
                            <table class="table" id="inspection-option-table">
                                <thead>
                                    <tr>
                                        <th class="text-center">ลำดับ</th>
                                        <th class="text-center">ชุดคำถาม</th>
                                        <th class="text-center">ประจำปี</th>
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
        <!-- ADD INSPECTION OPTION Modal -->
        <div class="modal fade" id="create-inspection-option-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">เพิ่มชุดคำถาม</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="create-inspection-option-form">
                            <div class="form-group">
                                <label>ชื่อชุดคำถาม</label>
                                <input type="text" class="form-control" name="inspectionOptionName" required>
                            </div>
                            <div class="form-group">
                                <label>ประจำปี</label>
                                <select class="custom-select" name="optionYear" required>
                                    <?php for ($i = 0; $i < 5; $i++) { ?>
                                        <option value="<?= date("Y") + 543 + $i ?>"><?= date("Y") + 543 + $i ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div id="result-create-inspection-option-form"></div>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END ADD INSPECTION OPTION Modal -->
    </div>

</div>