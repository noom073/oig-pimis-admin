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
                    <div class="h3">การตรวจราชการ</div>
                    <div class="h4">
                        <u><?= $plan['STANDFOR'] ?></u>
                    </div>
                    <div>
                        <small class="text-danger">ห้วงวันที่:
                            <?= date("d/m/Y", strtotime($plan['INS_DATE'])) ?>
                            -
                            <?= date("d/m/Y", strtotime($plan['FINISH_DATE'])) ?>
                        </small>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <div class="card-text">
                        <div>
                            <button class="btn btn-sm btn-light" onclick="return window.history.back();">ย้อนกลับ</button>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6">
                                <div class="list-group">
                                    <?php foreach ($inspections['odd'] as $inspection) { ?>
                                        <?php if ($inspection['INSPECTION_ID'] == null) { ?>
                                            <button type="button" class="list-group-item list-group-item-action inspect-list" data-inspection-id="<?= $inspection['INSPE_ID'] ?>"><?= $inspection['INSPE_NAME'] ?></button>
                                        <?php } else { ?>
                                            <a href="<?= site_url("auditor/inspected?plan={$plan['ID']}&inspectionID={$inspection['INSPE_ID']}") ?>" class="list-group-item list-group-item-action" title="บันทึกข้อมูลแล้ว" data-inspection-id="<?= $inspection['INSPE_ID'] ?>">
                                                <?= $inspection['INSPE_NAME'] ?>
                                                <small class="d-block text-success">**บันทึกข้อมูลแล้ว</small>
                                            </a>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="list-group">
                                    <?php foreach ($inspections['even'] as $inspection) { ?>
                                        <?php if ($inspection['INSPECTION_ID'] == null) { ?>
                                            <button type="button" class="list-group-item list-group-item-action inspect-list" data-inspection-id="<?= $inspection['INSPE_ID'] ?>"><?= $inspection['INSPE_NAME'] ?></button>
                                        <?php } else { ?>
                                            <a href="<?= site_url("auditor/inspected?plan={$plan['ID']}&inspectionID={$inspection['INSPE_ID']}") ?>" class="list-group-item list-group-item-action" title="บันทึกข้อมูลแล้ว" data-inspection-id="<?= $inspection['INSPE_ID'] ?>">
                                                <?= $inspection['INSPE_NAME'] ?>
                                                <small class="d-block text-success">**บันทึกข้อมูลแล้ว</small>
                                            </a>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-3">
                                <form id="auditor-inspect-form" class="d-none">
                                    <h5>ฟอร์ม</h5>
                                    <div id="form-questionaire"></div>
                                    <div>
                                        <button class="btn btn-sm btn-primary">บันทึก</button>
                                        <button class="btn btn-sm btn-light" type="button">ปิด</button>
                                    </div>
                                    <div id="result-auditor-score" class="invisible">
                                        รวม <span id="total-auditor-score"></span>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>