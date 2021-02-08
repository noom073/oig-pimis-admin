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
                    <div class="h3">การตรวจราชการ (<?= "{$teamPlan['TEAM_NAME']}  ปี {$teamPlan['TEAM_YEAR']}" ?>)</div>
                    <u class="h4 d-block"><?= $planDetail['NPRT_ACM'] ?></u>
                    <small class="text-muted d-block"><?= $planDetail['NPRT_NAME'] ?></small>
                    <small class="text-danger d-block">ห้วงวันที่: <?= "{$planDetail['INS_DATE']} ถึง {$planDetail['FINISH_DATE']}" ?></small>
                </div>
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <div class="card-text">
                        <div>
                            <button class="btn btn-sm btn-light" onclick="return window.history.back();">ย้อนกลับ</button>
                        </div>
                        <div class="pt-3">
                            <div>
                                <div class="mb-3">
                                    <label>ภาพปก</label>
                                    <button id="edit-main-photo-btn" class="btn btn-sm btn-success" data-team-plan-id="<?= $teamPlan['ROW_ID'] ?>">แก้ไข</button>
                                </div>
                                <?php if (isset($mainPhoto[0]['PIC_PATH'])) { ?>
                                    <div>
                                        <img src="<?= base_url("assets/filesUpload/{$mainPhoto[0]['PIC_PATH']}") ?>" class="img-thumbnail rounded mx-auto d-block w-50" alt="<?= $mainPhoto[0]['PIC_NAME'] ?>">
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div>
                            <?php foreach ($pictures as $r) { ?>
                                <div>
                                    <label><?= $r['INSPECTION_NAME'] ?></label>
                                    <button class="btn btn-sm btn-success d-block" data-team-plan-id="<?= $teamPlan['ROW_ID'] ?>" data-team-plan-id="<?= $r['TEAM_INSPECTION_ID'] ?>">แก้ไข</button>
                                    <div class="row">
                                        <?php foreach ($r['photos'] as $photo) { ?>
                                            <div class="col">
                                                <img src="<?= base_url("assets/filesUpload/{$photo['PIC_PATH']}") ?>" class="img-thumbnail rounded mx-auto d-block w-50" alt="<?= $photo['PIC_NAME'] ?>">
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div>
        <!-- EDIT MAIN PHOTO Modal -->
        <div class="modal fade" id="edit-main-photo" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">ภาพปก</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <?php foreach ($mainPhoto as $key => $r) { ?>
                                <div class="mb-3">
                                    <img src="<?= base_url("assets/filesUpload/{$r['PIC_PATH']}") ?>" class="img-thumbnail rounded mx-auto d-block" alt="<?= $r['PIC_NAME'] ?>">
                                    <div class="text-right">
                                        <button class="btn btn-sm btn-danger delete-main-photo" data-photo-row-id="<?= $r['ROW_ID'] ?>">ลบ</button>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <form id="edit-main-photo-form" enctype="multipart/form-data">
                            <input type="file" name="mainPhoto" class="form-control">
                            <button class="btn btn-primary">บันทึก</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </form>
                        <div id="edit-main-photo-form-result"></div>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <!-- END EDIT MAIN PHOTO Modal -->

        <!-- EDIT GALLERY PHOTO Modal -->
        <div class="modal fade" id="edit-gallery-photo" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">ภาพกิจกรรม</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <div class="mb-3">
                                <img src="<?= base_url("assets/filesUpload/{$r['PIC_PATH']}") ?>" class="img-thumbnail rounded mx-auto d-block" alt="<?= $r['PIC_NAME'] ?>">
                                <div class="text-right">
                                    <button class="btn btn-sm btn-danger delete-main-photo" data-photo-row-id="<?= $r['ROW_ID'] ?>">ลบ</button>
                                </div>
                            </div>
                        </div>
                        <form id="edit-main-photo-form" enctype="multipart/form-data">
                            <input type="file" name="mainPhoto" class="form-control">
                            <button class="btn btn-primary">บันทึก</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </form>
                        <div id="edit-main-photo-form-result"></div>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <!-- END EDIT GALLERY PHOTO Modal -->
    </div>
</div>